function Login(name, pass) {
    var name = eval('"' + name + '"');
    var pass = eval('"' + pass + '"');
    $.post(URL_LOGIN, {
            name: name,
            pass: pass
        },
        function(data) {
            if (data["status"] == 1) {
                layer.msg("登录成功", {
                    icon: 6
                });
                if(data["son"]){
                    setTimeout("window.location.href=GameIndex", 1000)
                }else{
                    setTimeout("window.location.href=Index", 1000)
                }
            } else if(data["status"] == 2){
                    layer.msg("系统异常", {
                        icon: 2
                    });
                    setTimeout("layer.closeAll()", 1000)
            } else if(data["status"] == 3){
                     layer.msg("系统异常", {
                            icon: 2
                     });
                     setTimeout("layer.closeAll()", 1000)
            } else if(data["status"] == 0) {
                     layer.msg("账号或密码错误", {
                                icon: 5
                     });
                            setTimeout("layer.closeAll()", 1000)
            } else if(data["status"] == 4) {
                     layer.msg("您的账号已被冻结", {
                                    icon: 5
                     })
            }else if(data["status"] == 5) {
                layer.msg("您所在的用户组无法登录", {
                    icon: 5
                })
            }

        },
        "json")
};
