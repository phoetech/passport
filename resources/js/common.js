var APPCommon = {
    iphoneSchema: "",
    iphoneDownUrl: "",
    androidSchema: "youthshine://",
    androidDownUrl:
        "http://ystatic.net/Android/youthshine.apk",
    openApp: function() {
        var this_ = this;
        if (this_.isWeixin()) {
            console.log("weixin");
            $(".weixin-tip").css("height", $(window).height());
            $(".weixin-tip").show();
            $(".weixin-tip").on("touchstart", function() {
                $(".weixin-tip").hide();
            });
        } else {
            console.log("liulanqi");
            if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
                console.log("iphone");
                // var loadDateTime = new Date();
                // window.setTimeout(function() {
                //     var timeOutDateTime = new Date();
                //     if (timeOutDateTime - loadDateTime < 5000) {
                //         window.location = this_.iphoneDownUrl; //ios下载地址
                //     } else {
                //         window.close();
                //     }
                // }, 25);
                // window.location = this.iphoneSchema;
            } else if (navigator.userAgent.match(/android/i)) {
                console.log("android");
                try {
                    window.location = this_.androidSchema;
                } catch (e) {
                    // console.log("error");
                }
            } else {
                console.log("pc");
            }
        }
    },
    isWeixin: function() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger") {
            return true;
        } else {
            return false;
        }
    }
};

window.isAndroid = function() {
    return navigator.userAgent.match(/Android/i);
};
