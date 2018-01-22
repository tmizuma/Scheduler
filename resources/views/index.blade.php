@extends('layouts.base')
@section('content')
<!--
 <div class="container index">
  <div class="row btn_right"><button type="button" class="btn btn-success">新規登録</button></div>
   <div id="app">
    <div class="container">
     <router-view></router-view>
    </div>
   </div>
 </div>
-->

<div class="container index">
  <div class="row height_100">
   <div class="col-sm-2 color_admin_left text-white">
      <div class="item"><a href ="/">電話番号管理</a></div>
   </div>
   <div class="col-sm-10 main_color">
    <div id="app" class="margin_top_20">
     <div class="container">
      <router-view></router-view>
     </div>
    </div>
   </div>
  </div>
</div>
@endsection