<div class="row">
    @if(session('message'))
        <article class="col-sm-12" id="tips">
            @if(session('message')['result'])
                <div class="alert alert-success fade in">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-check"></i>
                    <strong>成功</strong> {{ session('message')['content'] }}
                </div>
            @else
                <div class="alert alert-danger fade in">
                    <button class="close" data-dismiss="alert">×</button>
                    <i class="fa-fw fa fa-times"></i>
                    <strong>失败</strong> {{ session('message')['content'] }}
                </div>
            @endif
        </article>
    @endif
</div>