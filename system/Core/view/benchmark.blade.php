<style>
    div#bench-container * {
        margin: 0;
        padding: 0;
        text-decoration: none;
        box-sizing: border-box;
        font-size: 14px;
        font-family: monospace;

    }

    div#bench-container {
        max-height: 400px;
        margin: 0 auto;
        overflow: auto;
        z-index: 99999999999999;
        background-color: #1f1d1d;
        color: white;
        position: fixed;
        float: right;
        bottom: 0;
        right: 40px;
        padding: 0 0 5px 0;
        box-sizing: border-box;
        font-size: 14px;
        max-width: 900px;
        border-radius: 0;
        border:1px solid dodgerblue;
        /*min-width: calc(100% - 40px);*/
    }

    div#bench-container table tr td{
        padding: 5px;
    }
    div#bench-container table{
        margin-top: 10px;
    }

    button.bench_button {
        background-color: black;
        color: white;
        width: 40px;
        min-height: 40px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        align-content: center;
        position: fixed;
        bottom: 0;
        right: 0;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        border-radius: 0;
        border:1px solid dodgerblue;
    }

    p.http_status {
        background-color: #2b542c;
        color: white;
        padding: 10px 5px;
    }

    p.http_status span:first-child{
        display: inline-block;
        text-align: left;
        width: 49%;
    }

    span.bench_app_name {
        text-align: right;
        color: white;
        font-weight: bold;
        display: inline-block;
        width: 49%;
    }
    div#bench-autohide-loadtime{
        display: inline-block;
        position: absolute;
        padding:0 15px;
        width: 200px;
        text-align: center;
        background-color: #00a65a;
        border:1px solid dodgerblue;
        color:#fff;
        font-weight:bold;
        top:-50px;
        right: 10px;
        z-index: calc(999 * 999);
        animation: loadtime 10s 1;
        -moz-animation: loadtime 10s 1;
        -webkit-animation:loadtime 10s 1;
        transition: 0.7s;

    }
    @keyframes loadtime{
        5%{
            opacity: 1;
            top:0;
        }
        30%{
            opacity:1;
            top:10px;
        }
        50%{
            opacity: 0.8;
            right:6px;
            transition: 0.7s;
        }
        80%{
            opacity: 0.7;
            right:3px;
            transition: 0.7s;
        }
        90%{
            opacity: 0.6;
            right:0;
            transition: 0.7s;
        }
        100%{
            opacity: 0.1;
            right: -200px;
            display: none;
        }
    }
    @-webkit-keyframes loadtime{
        5%{
            opacity: 1;
            top:0;
        }
        30%{
            opacity:1;
            top:10px;
        }
        50%{
            opacity: 0.8;
            right:6px;
            transition: 0.7s;
        }
        80%{
            opacity: 0.7;
            right:3px;
            transition: 0.7s;
        }
        90%{
            opacity: 0.6;
            right:0;
            transition: 0.7s;
        }
        100%{
            opacity: 0.1;
            right: -200px;
            display: none;
        }
    }
</style>
<div id="bench-autohide-loadtime">
    load time : {{substr($data['Load time'],0,-6)}}
</div>
<div id="bench-container" style="display: none">
    <p class="http_status">
        <span>{{http_response_code()}}</span>
        <span class="bench_app_name">{{setting ( 'APP_NAME' , 'TT' )}}</span>
    </p>
    <p>
        <span style="color:green"> <span>root@</span>{{strtolower(setting('APP_NAME', 'TT'))}}</span>
        :~<span style="color:red">#</span> benchmark
    </p>
    <table border="1">
        @foreach ($data as $name => $value)
            <tr>
                <td><i style="color:rgb(190, 49, 3)">{{$name}}</i></td>
                <td><i style="color:green">{{$value}}</i></td>
            </tr>
        @endforeach
    </table>
</div>
<button onclick="benchToggle(this)" class="bench_button">B</button>
<script>

    function benchToggle($this) {
        let bench = document.getElementById("bench-container");
        if (bench.style.display !== "none") {
            $this.style.height = "40px";
            $this.innerHTML = "B";
            bench.style.display = "none";
        }
        else {
            $this.innerHTML = "X";
            bench.style.display = "inline-block";
            $this.style.height = bench.offsetHeight + "px";
        }
    }
</script>