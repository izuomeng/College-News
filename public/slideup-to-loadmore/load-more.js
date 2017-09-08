/**
 * Created by Chaiyunfeng on 2017/7/27.
 */
var LoadMore = (function () {
    var screenY = 0,
        cursor = 0;
    function LoadMore(content, loading) {
        this.content = content;
        this.loading = loading;
    }
    LoadMore.prototype = {
        constructor : LoadMore,
        scrollEvent : function () {
            screenY = document.body.scrollHeight - document.body.scrollTop;
            var windowHeight = window.innerHeight || document.documentElement.clientHeight;
            if (screenY <= windowHeight) {
                requestData(this);
                this.loading.style.display = 'block';
                this.loading.innerHTML = '加载中...';
            }
        },
        init : function () {
            window.onload = requestData(this);
            window.onscroll = this.scrollEvent.bind(this);
        }
    };
    function requestData(o) {
        var request = new XMLHttpRequest();
        request.open('get', 'sentData.php?cursor='+cursor);
        cursor += 10;
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                o.loading.style.display = 'none';
                showData(o.content, request.responseText);
            }
        };
        request.send(null);
    }
    function showData(content, data) {
        var fragment = document.createDocumentFragment(),
            text = JSON.parse(data);
        if (text) {
            for (var i in text) {
                var div = document.createElement('div');
                if (text.hasOwnProperty(i)){
                    div.innerHTML = text[i];
                    fragment.appendChild(div);
                }
            }
            content.appendChild(fragment);
        }
    }
    return LoadMore;
})();