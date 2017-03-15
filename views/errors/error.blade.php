<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Error {{ $code }}</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    @include('backend::layouts.link')
    <style>
        .error-text-2 {
            text-align: center;
            font-size: 700%;
            font-weight: bold;
            font-weight: 100;
            color: #333;
            line-height: 1;
            letter-spacing: -.05em;
            background-image: -webkit-linear-gradient(92deg,#333,#ed1c24);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .particle {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1rem;
            height: 1rem;
            border-radius: 100%;
            background-color: #ed1c24;
            background-image: -webkit-linear-gradient(rgba(0,0,0,0),rgba(0,0,0,.3) 75%,rgba(0,0,0,0));
            box-shadow: inset 0 0 1px 1px rgba(0,0,0,.25);
        }
        .particle--a {
            -webkit-animation: particle-a 1.4s infinite linear;
            -moz-animation: particle-a 1.4s infinite linear;
            -o-animation: particle-a 1.4s infinite linear;
            animation: particle-a 1.4s infinite linear;
        }
        .particle--b {
            -webkit-animation: particle-b 1.3s infinite linear;
            -moz-animation: particle-b 1.3s infinite linear;
            -o-animation: particle-b 1.3s infinite linear;
            animation: particle-b 1.3s infinite linear;
            background-color: #00A300;
        }
        .particle--c {
            -webkit-animation: particle-c 1.5s infinite linear;
            -moz-animation: particle-c 1.5s infinite linear;
            -o-animation: particle-c 1.5s infinite linear;
            animation: particle-c 1.5s infinite linear;
            background-color: #57889C;
        }@-webkit-keyframes particle-a {
             0% {
                 -webkit-transform: translate3D(-3rem,-3rem,0);
                 z-index: 1;
                 -webkit-animation-timing-function: ease-in-out;
             } 25% {
                   width: 1.5rem;
                   height: 1.5rem;
               }

             50% {
                 -webkit-transform: translate3D(4rem, 3rem, 0);
                 opacity: 1;
                 z-index: 1;
                 -webkit-animation-timing-function: ease-in-out;
             }

             55% {
                 z-index: -1;
             }

             75% {
                 width: .75rem;
                 height: .75rem;
                 opacity: .5;
             }

             100% {
                 -webkit-transform: translate3D(-3rem,-3rem,0);
                 z-index: -1;
             }
         }

        @-moz-keyframes particle-a {
            0% {
                -moz-transform: translate3D(-3rem,-3rem,0);
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                -moz-transform: translate3D(4rem, 3rem, 0);
                opacity: 1;
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .75rem;
                height: .75rem;
                opacity: .5;
            }

            100% {
                -moz-transform: translate3D(-3rem,-3rem,0);
                z-index: -1;
            }
        }

        @-o-keyframes particle-a {
            0% {
                -o-transform: translate3D(-3rem,-3rem,0);
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                -o-transform: translate3D(4rem, 3rem, 0);
                opacity: 1;
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .75rem;
                height: .75rem;
                opacity: .5;
            }

            100% {
                -o-transform: translate3D(-3rem,-3rem,0);
                z-index: -1;
            }
        }

        @keyframes particle-a {
            0% {
                transform: translate3D(-3rem,-3rem,0);
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                transform: translate3D(4rem, 3rem, 0);
                opacity: 1;
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .75rem;
                height: .75rem;
                opacity: .5;
            }

            100% {
                transform: translate3D(-3rem,-3rem,0);
                z-index: -1;
            }
        }

        @-webkit-keyframes particle-b {
            0% {
                -webkit-transform: translate3D(3rem,-3rem,0);
                z-index: 1;
                -webkit-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                -webkit-transform: translate3D(-3rem, 3.5rem, 0);
                opacity: 1;
                z-index: 1;
                -webkit-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -webkit-transform: translate3D(3rem,-3rem,0);
                z-index: -1;
            }
        }

        @-moz-keyframes particle-b {
            0% {
                -moz-transform: translate3D(3rem,-3rem,0);
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                -moz-transform: translate3D(-3rem, 3.5rem, 0);
                opacity: 1;
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -moz-transform: translate3D(3rem,-3rem,0);
                z-index: -1;
            }
        }

        @-o-keyframes particle-b {
            0% {
                -o-transform: translate3D(3rem,-3rem,0);
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                -o-transform: translate3D(-3rem, 3.5rem, 0);
                opacity: 1;
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -o-transform: translate3D(3rem,-3rem,0);
                z-index: -1;
            }
        }

        @keyframes particle-b {
            0% {
                transform: translate3D(3rem,-3rem,0);
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.5rem;
                height: 1.5rem;
            }

            50% {
                transform: translate3D(-3rem, 3.5rem, 0);
                opacity: 1;
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                transform: translate3D(3rem,-3rem,0);
                z-index: -1;
            }
        }

        @-webkit-keyframes particle-c {
            0% {
                -webkit-transform: translate3D(-1rem,-3rem,0);
                z-index: 1;
                -webkit-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.3rem;
                height: 1.3rem;
            }

            50% {
                -webkit-transform: translate3D(2rem, 2.5rem, 0);
                opacity: 1;
                z-index: 1;
                -webkit-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -webkit-transform: translate3D(-1rem,-3rem,0);
                z-index: -1;
            }
        }

        @-moz-keyframes particle-c {
            0% {
                -moz-transform: translate3D(-1rem,-3rem,0);
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.3rem;
                height: 1.3rem;
            }

            50% {
                -moz-transform: translate3D(2rem, 2.5rem, 0);
                opacity: 1;
                z-index: 1;
                -moz-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -moz-transform: translate3D(-1rem,-3rem,0);
                z-index: -1;
            }
        }

        @-o-keyframes particle-c {
            0% {
                -o-transform: translate3D(-1rem,-3rem,0);
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.3rem;
                height: 1.3rem;
            }

            50% {
                -o-transform: translate3D(2rem, 2.5rem, 0);
                opacity: 1;
                z-index: 1;
                -o-animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                -o-transform: translate3D(-1rem,-3rem,0);
                z-index: -1;
            }
        }

        @keyframes particle-c {
            0% {
                transform: translate3D(-1rem,-3rem,0);
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            25% {
                width: 1.3rem;
                height: 1.3rem;
            }

            50% {
                transform: translate3D(2rem, 2.5rem, 0);
                opacity: 1;
                z-index: 1;
                animation-timing-function: ease-in-out;
            }

            55% {
                z-index: -1;
            }

            75% {
                width: .5rem;
                height: .5rem;
                opacity: .5;
            }

            100% {
                transform: translate3D(-1rem,-3rem,0);
                z-index: -1;
            }
        }
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
        }
        #error-content {
            padding-top: 15%;
            overflow: hidden;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="row" id="error-content">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-center error-box">
                    <h1 class="error-text-2 bounceInDown animated"> Error {{ $code }} <span class="particle particle--c"></span><span class="particle particle--a"></span><span class="particle particle--b"></span></h1>
                    <h2 class="font-xl">
                        <strong>
                            <i class="fa fa-fw fa-warning fa-lg text-warning"></i>
                            @if($code == 404)
                                页面未找到
                            @elseif($code == 403)
                                页面未授权
                            @else
                                有错误发生！
                            @endif
                        </strong>
                    </h2>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<!-- row -->
</html>


