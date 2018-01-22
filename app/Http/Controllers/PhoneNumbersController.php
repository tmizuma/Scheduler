<?php

namespace App\Http\Controllers;
use App\Http\Service\KeywordService;
use App\Http\Service\PhoneNumbersService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PhoneNumbersController extends AjaxController {

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
            $this->data = $this->phoneNumbersService->findAllPhoneNumbers();
        return $this->response();
    }

    public function show($id) {
        $this->data = $this->phoneNumbersService->findById($id);
        return $this->response();
    }

    public function store() {
        $this->validate($this->request,[
            'phone_number' 		=> ['required','string']
        ]);
        $this->phoneNumbersService->createPhoneNumber([
            'phone_number'      => $this->request->input('phone_number'),
            'description'       => empty($this->request->input('description')) ? '' : $this->request->input('description'),
            'department'        => empty($this->request->input('department')) ? '' : $this->request->input('department'),
            'person_in_charge'  => empty($this->request->input('person_in_charge')) ? '' : $this->request->input('person_in_charge'),
        ]);
    }

    public function update($id) {
        $this->validate($this->request,[
            'phone_number' 		=> ['required','string'],
        ]);
        $this->phoneNumbersService->updatePhoneNumber($id, [
            'phone_number'      => $this->request->input('phone_number'),
            'description'       => empty($this->request->input('description')) ? '' : $this->request->input('description'),
            'department'        => empty($this->request->input('department')) ? '' : $this->request->input('department'),
            'person_in_charge'  => empty($this->request->input('person_in_charge')) ? '' : $this->request->input('person_in_charge'),
            'updated_at'        => Carbon::now()
        ]);
    }

    public function destroy($id) {
        $this->keywordService->deleteByPhoneNumberId($id);
        $this->phoneNumbersService->deletePhoneNumber($id);
    }
}
