<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Phoetech Passport</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Phoetech." />
        <meta name="keywords" content="Phoetech" />
        <meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
        <meta content="yes" name="apple-mobile-web-app-capable"/>
        <meta content="black" name="apple-mobile-web-app-status-bar-style"/>
        <meta content="telephone=no" name="format-detection"/>
        <meta name="Jim" content="Phoetech Passport" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link href="{{mix('css/app.css')}}" rel="stylesheet" type="text/css" media="screen" />
        <link href="/css/all.css" rel="stylesheet">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    </head>

<body>
  <div  id="app" v-cloak>
  <!-- 导航 -->
  <header>
    <div class="main">
      <div class="fl left"><img src="images/logo.png"></div>
      <div class="fr nav"> 
        <ul class="navbar_nav" data-in="fadeInDown" data-out="fadeOutUp">
          <li class="dropdown">
            <a href="/lang?" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{[langList[lang]]} <img src="/img/icon/downmenu-gray.svg" width="12"></a>
              <div class="dropdown-menu lang-menu arrow_index_box" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#" v-for="(la, key) in langList" @click.prevent="setLang(key)">{[ la ]}</a>
              </div>
          </li>
        </ul>
      </div>
    </div>
  </header>
    
  <section class="content-flexView">
      @yield('content')
      <footer class="login-footer-link"><a href="#">Home</a> | <a href="#">Privace Policy</a> | <a href="#">Policies and Procedures</a></a></footer>    
  </section>
  </div>
  <script>
    window.LJ = {
      lang: @json(App::getLocale())
    };

  </script>
  <script src="{{mix('js/app.js') }}"></script>
  <script src="{{mix('js/common.js') }}"></script>
</body>
</html>
