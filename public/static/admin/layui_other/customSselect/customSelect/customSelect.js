layui.define(['tree'], function (exports) {
    const tree = layui.tree,
        Modules = 'customSelect',
        CustomSelect = function () {
        };

    CustomSelect.prototype.render = function (options) {
        let el = options.el,
            elem = '#' + el,
            $el = $(elem),
            tem = new Date().getTime(),
            list = el + 'SelectDom' + tem,
            listm = '#' + list,
            datas = options.data,
            showLine = options.line === undefined ? true : options.line,
            accordion = options.accordion === false ? true : options.accordion,
            type = options.type,
            checked = options.checked,
            checkBoxDiv = `<div id="${list}" class="layui-anim layui-anim-upbit" style="display: none;position: absolute; top: 41px; left: 0; padding: 5px 0; width: 100%; max-height: 350px; overflow-y: scroll; background: #FFFFFF"></div>`

        let show = false
        let checks = [], checkStr = []
        let handle = {
            checks: [],
            checkStr: [],
            init: function () {
                const _self = this
                $el.append(checkBoxDiv)
                $el.find('input').click(function () {
                    show = !show
                    if (show) $(listm).show()
                    else $(listm).hide()
                })
                function treeForeach (tree) {//遍历树，添加title
                    return tree.forEach(data => {
                        if(!data.title || data.title === undefined) {
                            data.title = data.name || data.text
                            data.children && treeForeach(data.children)
                        }
                    })
                }
                treeForeach(datas)
                tree.render({
                    elem: listm,
                    data: datas,
                    showCheckbox: type !== 'radio',
                    onlyIconControl: type === 'radio',
                    showLine: showLine,
                    accordion: accordion,
                    click: function (obj) {
                        if (type === 'radio') {
                            if (obj.data.children) {
                                layui.layer.msg('请选择子节点！', {icon: 0, offset: '10px'})
                                return
                            }
                            $(listm).find('span').css('color', '')
                            $(obj.elem).find('span').css('color', 'red')
                            _self.onclick(obj)
                        }
                    },
                    oncheck: function (obj) {
                        _self.onchecked(obj)
                    }
                })
                $('body').click(function () {
                    $(listm).hide()
                    show = false
                })
                $(listm).click(function () {
                    event.stopPropagation()
                })
            },
            onclick: function (data) {
                $el.find('input').val(data.data.title)
                checked({value: data.data.title, data: data.data})
                $(listm).hide()
                show = false
            },
            onchecked: function (data) {
                if(data.checked) {
                    if(data.data.children) {
                        for(let i in data.data.children) {
                            checks.push(data.data.children[i])
                            checkStr.push(data.data.children[i].title)
                        }
                    } else {
                        checks.push(data.data)
                        checkStr.push(data.data.title)
                    }
                } else {
                    if(data.data.children) {
                        for(let n = 0;n < data.data.children.length;n ++) {
                            for(let i = 0;i < checks.length;i ++) {
                                if(checks[i].code = data.data.children[n].code) {
                                    checks.splice(i, 1)
                                    checkStr.splice(i, 1)
                                }
                            }
                        }
                    } else {
                        for(let i = 0;i < checks.length;i ++) {
                            if(data.data.code == checks[i].code) {
                                checks.splice(i, 1)
                                checkStr.splice(i, 1)
                            }
                        }
                    }
                }
                for(let i in checks) {
                    checkStr.join(i.title)
                }
                checked({value: checkStr, data: checks})
            }
        }
        handle.init()
        return new CustomSelect()
    }
    exports(Modules, new CustomSelect())
})