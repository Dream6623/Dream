<?php

namespace App\Http\Service;

use Elasticsearch\ClientBuilder;

/**
 * Es搜索 + 高亮 + 分词
 */

class Es
{
    public static function create_index($index, $type, $fields)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => $index,
            'body' => [
                'mappings' => [
                    $type => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            $fields => [
                                'type' => 'text',
                                'analyzer' => 'ik_max_word',
                                'search_analyzer' => 'ik_max_word'
                            ]
                        ]
                    ]
                ]
            ],
            "include_type_name" => true
        ];
        return $client->indices()->create($params);
    }

    public static function Add($index, $type, $id, $body)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();

        $params = [
            'index' => $index,
            'type' => $type,
            'id' => $id,
            'body' => $body
        ];

        return $client->index($params);
    }

    public static function search($index, $search)
    {
        $client = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
        $params = [
            'index' => $index,
            'type' => '_doc',
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $search
                    ]
                ],
                'highlight' => [
                    'fields' => [
                        'title' => [
                            'pre_tags' => ["<span style='color: red'>"],
                            'post_tags' => ["</span>"]
                        ]
                    ]
                ]
            ]
        ];

//        return $client->search($params);
        $res = $client->search($params);
        foreach ($res['hits']['hits'] as $k => $v) {
            $data[$k]['_source']['title'] = $v['highlight']['title'][0];
        }
        $title = array_column($data, '_source');
        return $title;
    }
}
