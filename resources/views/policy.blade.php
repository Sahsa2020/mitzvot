@extends('layouts.app')

@section('content')
<div class="background-policy">
    
</div>
<div class="container" style="margin-top: 40px;">
    <div class="row">
        <h1 class="text-center">Privacy Policy</h1>
        <h2>1. Title1 </h2>
        <p class="content"> This is content for Title1</p>
        <h2>2. Title2 </h2>
        <p class="content"> This is content for Title2</p>
        <h2>3. Title3 </h2>
        <p class="content"> This is content for Title3</p>
    </div>
</div>
@endsection

<style type="text/css">
.background-policy{
    height: 500px;
    background: url(/rule/background.jpg) rgb(130, 182, 82);
    margin-top: -20px;
}
.content{
    margin-left: 20px;
}
</style>