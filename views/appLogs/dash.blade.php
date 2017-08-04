@extends('backend::layouts.master')
@section('style')
    <style>
        #dash {
            background-color: #fafafa;
            padding: 40px;
        }
        .dash-table {
            position: relative;
            background-color: #ffffff;
            box-shadow: 0 1px 6px 0 rgba(185, 185, 185, 0.5);
            height: 365px;
            margin-top: 40px;
            margin-bottom: 40px;
            border-color: #fe7961;
        }
        table {
            width: 100%;
            font-size: 16px;
            letter-spacing: 2.4px;
            color: #686868;
        }
        .dash-table td {
            border: 1px solid #EEEEEE;
            padding: 29px 0;
            text-align: center;
        }
        .dash-table:after {
            position: absolute;
            top: 0;
            right: 40px;
            content: '';
            border-color: #fe7961;
            border-style: solid;
            border-left-width: 12px;
            border-right-width: 12px;
            border-top-width: 30px;
            border-bottom: 8px solid transparent;
        }
        .dash-table-title {
            position: relative;
            font-size: 26px;
            font-weight: 500;
            letter-spacing: 3.9px;
            color: #303030;
            padding: 32px;
            height: 122px;
        }
        .dash-table-title:before {
            position: absolute;
            left: 0;
            width: 8px;
            height: 32px;
            content: '';
        }
        .dash-table:nth-of-type(4):after {
            border-color: #fe7961;
            border-bottom-color: transparent;
        }
        .dash-table:nth-of-type(6):after {
            border-color: #3ee2b0;
            border-bottom-color: transparent;
        }
        .dash-table:nth-of-type(8):after {
            border-color: #ffbb3b;
            border-bottom-color: transparent;
        }
        .dash-table:nth-of-type(10):after {
            border-color: #60a7ff;
            border-bottom-color: transparent;
        }
        .dash-table:nth-of-type(12):after {
            border-color: #f576c5;
            border-bottom-color: transparent;
        }
        .dash-table:nth-of-type(4) .dash-table-title:before {
            background-color: #fe7961;
        }
        .dash-table:nth-of-type(6) .dash-table-title:before {
            background-color: #3ee2b0;
        }
        .dash-table:nth-of-type(8) .dash-table-title:before {
            background-color: #ffbb3b;
        }
        .dash-table:nth-of-type(10) .dash-table-title:before {
            background-color: #60a7ff;
        }
        .dash-table:nth-of-type(12) .dash-table-title:before {
            background-color: #f576c5;
        }

        .dash-table:nth-of-type(4) tr:nth-of-type(2) td:not(:first-child),
        .dash-table:nth-of-type(6) tr:nth-of-type(2) td:not(:first-child),
        .dash-table:nth-of-type(8) tr:nth-of-type(2) td:not(:first-child),
        .dash-table:nth-of-type(10) tr:nth-of-type(2) td:not(:first-child),
        .dash-table:nth-of-type(12) tr:nth-of-type(2) td:not(:first-child) {
            color: #303030;
            font-weight: 500;
        }

        .dash-table:nth-of-type(4) tr:nth-of-type(3) td:not(:first-child) {
            color: #fe7961;
            font-weight: 500;
        }
        .dash-table:nth-of-type(6) tr:nth-of-type(3) td:not(:first-child){
            color: #3ee2b0;
            font-weight: 500;
        }
        .dash-table:nth-of-type(8) tr:nth-of-type(3) td:not(:first-child) {
            color: #ffbb3b;
            font-weight: 500;
        }
        .dash-table:nth-of-type(10) tr:nth-of-type(3) td:not(:first-child){
            color: #60a7ff;
            font-weight: 500;
        }
        .dash-table:nth-of-type(12) tr:nth-of-type(3) td:not(:first-child) {
            color: #f576c5;
            font-weight: 500;
        }
        h4 {
            font-size: 20px;
            font-weight: 500;
            line-height: 1.5;
            color: #303030;
        }
        .description {
            font-size: 18px;
            line-height: 1.67;
            letter-spacing: 3px;
            color: #686868;
        }
        .dash-header {
            border-bottom: 2px solid #eeeeee;
        }
        .dash-time {
            display: flex;
            justify-content: space-between;
            font-size: 18px;
            letter-spacing: 3px;
            color: #686866;
        }
        .dash-block {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .dash-block-wrapper {
            border-radius: 8px;
            background-color: #60a7ff;
            width: 252px;
            height: 92px;
            padding: 20px;
        }
        .dash-block-content {
            margin: 20px;
        }
        .wrapper-img {
            float: left;
            background-color: #509af5;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            margin-right: 18px;
        }
        .dash-block-wrapper:nth-of-type(2) {
            background-color: #ffbc3b;
        }
        .dash-block-wrapper:nth-of-type(3) {
            background-color: #2ae390;
        }
        .dash-block-wrapper:nth-of-type(4) {
            background-color: #fe7961;
        }
        .dash-block-wrapper:nth-of-type(2) .wrapper-img {
            background-color: #f2ac28;
        }
        .dash-block-wrapper:nth-of-type(3) .wrapper-img {
            background-color: #23d686;
        }
        .dash-block-wrapper:nth-of-type(4) .wrapper-img {
            background-color: #f86a51;
        }
        .wrapper-img img {
            margin: 13px 14px;
        }
        .wrapper-body {
            text-align: center;
        }
        .wrapper-body h4 {
            font-size: 20px;
            font-weight: 500;
            letter-spacing: 3.3px;
            color: #ffffff;
        }
        .wrapper-body p {
            font-size: 14px;
            letter-spacing: 2.1px;
            color: #f1f1f1;
            margin: 0;
        }
    </style>
@endsection
@section('content')
<div id="dash">
    <div class="dash-header">
        <h4>您好！管理员</h4>
        <div class="dash-time">
            <div class="description">您可以通过以下总览信息最快速了解APP运行数据变更信息</div>
            <div>{{ date('Y-m-d') }}</div>
        </div>
    </div>
    <div class="dash-block">
        <div class="dash-block-wrapper">
            <div class="wrapper-body">
                <p>全平台调用API(总次数)</p>
                <h4>{{ $totalCount }}</h4>
            </div>
        </div>
        <div class="dash-block-wrapper">
            <div class="wrapper-body">
                <p>API请求耗时(平均/毫秒)</p>
                <h4>{{ $avgConsumeTime }}</h4>
            </div>
        </div>
        <div class="dash-block-wrapper">
            <div class="wrapper-body">
                <p>登录用户调用API(总次数)</p>
                <h4>{{ $loginCount }}</h4>
            </div>
        </div>
        <div class="dash-block-wrapper">
            <div class="wrapper-body">
                <p>未登录用户调用API(总次数)</p>
                <h4>{{ $noLoginCount }}</h4>
            </div>
        </div>
    </div>

    @foreach($data as $table)
    <div class="dash-header">
        <h4>{{ $table['title'] }}</h4>
        <div class="description">{{ $table['description'] }}</div>
    </div>
    <div class="dash-table">
        <div class="dash-table-title">{{ $table['title'] }}</div>
        <table>
            @foreach($table['row'] as $item)
            <tr>
                @foreach($item as $value)
                <td>{{ is_numeric($value) ? number_format($value) : $value ?: 0 }}</td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>
    @endforeach
</div>
@endsection