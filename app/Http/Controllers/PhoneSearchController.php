<?php

namespace App\Http\Controllers;
use App\Http\Service\KeywordService;
use App\Http\Service\PhoneNumbersService;
use Illuminate\Http\Request;

class PhoneSearchController extends AjaxController {

    const LIFEASSISTS_USER_NAME = 'lifeassists';

    /** @var Request  */
    private $request;
    /** @var PhoneNumbersService  */
    private $phoneNumbersService;
    /** @var KeywordService  */
    private $keywordService;
    
    public function __construct( PhoneNumbersService $phoneNumbersService, KeywordService $keywordService, Request $request ) {
        $this->request = $request;
        $this->phoneNumbersService = $phoneNumbersService;
        $this->keywordService = $keywordService;
    }

    public function index() {
        $this->validate($this->request,[
            'keywords' 		=> ['required','string']
        ]);
        $keywords = $this->request->input('keywords');
        $keywords = str_replace(self::LIFEASSISTS_USER_NAME,"", $keywords);
        $keywords = str_replace(","," ", $keywords);
        $search_words = str_replace([" ","　"],",", $keywords);
        $search_word_array = $items = explode( ",", $search_words);
        $this->data = $this->phoneNumbersService->searchTargetPhoneNumber($search_word_array);
        return $this->response();
    }

}
