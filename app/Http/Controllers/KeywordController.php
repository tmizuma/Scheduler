<?php

namespace App\Http\Controllers;
use App\Http\Component\StatusCode;
use App\Http\Service\KeywordService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KeywordController extends AjaxController {

    /** @var Request  */
    private $request;

    /** @var KeywordService  */
    private $keywordService;

    public function __construct( KeywordService $keywordService, Request $request ) {
        $this->request = $request;
        $this->keywordService = $keywordService;
    }

    public function show($id) {
        $this->data = $this->keywordService->getKeywordsById($id);
        return $this->response();
    }
    
    public function update($id) {
        if (is_int($id)) {
            $this->status = StatusCode::INVALID_ARGUMENT;
            return $this->response();
        }
        $this->validate($this->request,[
            'keywords' 		=> ['required','string']
        ]);
        $items = explode( ",", $this->request->input('keywords'));
        $keywords = [];
        foreach ($items as $item) {
            $keywords[] = ['phone_number_id' => (int)$id, 'keyword' => $item, 'created_at' => Carbon::now()];
        }
        if (empty($keywords)) {
            $this->status = StatusCode::INVALID_ARGUMENT;
            return $this->response();
        }
        $this->keywordService->updateKeywordById($id, $keywords);
        return $this->response();
    }
}
