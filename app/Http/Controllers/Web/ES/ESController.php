<?php

namespace App\Http\Controllers\Web\ES;

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class ESController extends Controller
{
    //
    private $client;

    public function __construct()
    {
        $hosts = [
            'elastic:devide@126.com@192.168.20.89:9200',
        ];
        $this->client = ClientBuilder::create()           // Instantiate a new ClientBuilder
        ->setHosts($hosts)      // Set the hosts
        ->build();
    }

    public function getorders(Request $request)
    {
        $result = [];
        $godate = $request->rq;
        $params = [
            'index' => 'order',
            'body' => [
                'query' => [
                    'match' => [
                        'tour_date' => $godate
                    ]
                ],
                'from' => 0,
                'size' => 100,
                'sort' => [
                    'id' => [
                        'order' => 'desc'
                    ]
                ]
            ]
        ];
        $response = $this->client->search($params);
        $res = $response['hits']['hits'];
        $orders = array_column($res, '_source');
        foreach ($orders as $order) {
            $selfpay_je = 0;
            $orderid = $order['id'];
            //查询自费项目
            $selfpay_parm = [
                'index' => 'order_selfpaying',
                'from' => 0,
                'size' => 1000,
                'body' => [
                    'query' => [
                        'match' => [
                            'order_id' => $orderid
                        ]
                    ]
                ]
            ];
            $selfpays = array_column($this->client->search($selfpay_parm)['hits']['hits'], '_source');
            $selfpay_je = collect($selfpays)->sum('amount');
            //查询订单客人
            $guestparm = [
                'index' => 'order_guest',
                'from' => 0,
                'size' => 1000,
                'body' => [
                    'query' => [
                        'match' => [
                            'order_id' => $orderid
                        ]
                    ]
                ]
            ];
            $guests = array_column($this->client->search($guestparm)['hits']['hits'], '_source');
            $orderguests = collect($guests)->map(function ($item) {
                return [
                    'name' => $item['name'],
                    'sex' => $item['sex'],
                    'birthday' => $item['birthday'],
                    'city' => $item['city'],
                    'cert_type' => $item['cert_type'],
                    'cert_no' => $item['cert_no']
                ];
            });
            $data = [
                'order_id' => $order['id'],
                'order_no' => $order['order_no'],
                'create_name' => $order['create_name'],
                'tour_date' => $order['tour_date'],
                'amount' => $order['amount'] + $selfpay_je,
                'status' => $order['status'],
                'guests' => $orderguests
            ];
            array_push($result, $data);
        }
        return $result;
    }

    public function getlocalusers(Request $request)
    {

        $queryparm = [
            'index' => 'sys_user',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [

                        ]
                    ]
                ]
            ]
        ];
        if ($request->name) {
            array_push($queryparm['body']['query']['bool']['must'],
                array(
                    'match' => array(
                        'name' => $request->name
                    )
                )
            );
        }
        if ($request->code) {
            array_push($queryparm['body']['query']['bool']['must'],
                array(
                    'match' => array(
                        'usercode' => $request->code
                    )
                )
            );
        }
        //dd($queryparm);
        $response_result = array_column($this->client->search($queryparm)['hits']['hits'], '_source');
        return collect($response_result)->map(function ($item) {
            return [
                'usercode' => $item['usercode'],
                'name' => $item['name'],
                'tel' => $item['tel'],
                'adress' => $item['adress'],
                'idno' => $item['idno']
            ];
        });
    }

}
