/**
 * Created by Chaiyunfeng on 2017/7/20.
 */
function Drop(dropDiv, contentDiv) {
    this.dropArea = dropDiv;
    this.content = contentDiv;
    this.start = 0;
    this.end = 0;
    this.curser = 0;
    this.deltaY = 0;
    this.status = 'closed';
}
Drop.prototype = {
    refreshState : function (div) {
        div.style.transition = '.4s';
        div.style.height = '100px';
        div.innerHTML = '刷新中...';
    },
    showData : function (data, content) {
        var p = document.createDocumentFragment();
        try{
            data = JSON.parse(data); //data在这里是数组
        } catch(e) {
            console.log(e);
        }
        if (typeof data === 'object'){
            for(var i in data){
                var div = document.createElement('div');
                div.className = 'data';
                if (data.hasOwnProperty(i)){
                    div.innerHTML = data[i];
                }
                p.appendChild(div);
            }
            content.innerHTML = '';
            content.appendChild(p);
        }else {
            content.innerHTML = data;
        }
        this.dropArea.style.transition = '.2s';
        this.dropArea.style.height = '0';
        this.status = 'closed';
    },
    requestData : function (url) {
        var that = this;
        url = url + '?curser=' + this.curser;
        var request = new XMLHttpRequest();
        request.open('get', url);
        request.onreadystatechange = function () {
            if(request.readyState === 4 && request.status === 200){
                console.log(request.responseText);
                that.showData(request.responseText, that.content);
                that.curser += 10;
            }
        };
        request.send(null);
    },
    getTouch : function (event) {
        if(document.body.scrollTop > 0){
            return;
        }
        switch (event.type){
            case 'touchstart':
                this.dropArea.style.transition = '0';
                if (this.status === 'closed'){
                    this.dropArea.style.opacity = '0';
                }
                this.start = event.touches[0].clientY;
                break;
            case 'touchmove':
                this.end = event.touches[0].clientY;
                this.deltaY = Math.round(this.end - this.start);
                if (this.status === 'closed'){
                    if(this.deltaY > 0){
                        event.preventDefault();
                        if (this.deltaY*0.4 < 200){
                            this.dropArea.innerHTML = '下滑刷新';
                        }else {
                            this.dropArea.innerHTML = '释放刷新';
                        }
                        this.dropArea.style.display = 'block';
                        this.dropArea.style.height = this.deltaY*0.4 + 'px';
                        this.dropArea.style.opacity = this.deltaY/400;
                    }
                }else if (this.status === 'refreshing'){
                    if(this.deltaY > 0){
                        event.preventDefault();
                    }
                    this.dropArea.style.height = (100 + this.deltaY*0.4) + 'px';
                }
                break;
            case 'touchend':
                if (this.status === 'closed'){
                    if (this.deltaY*0.4 > 200){
                        this.refreshState(this.dropArea);
                        this.status = 'refreshing';
                        setTimeout(this.requestData.bind(this), 1000, 'sentData.php');
                    }else {
                        this.dropArea.style.transition = '.2s';
                        this.dropArea.style.height = '0';
                    }
                }else if (this.status === 'refreshing'){
                    this.refreshState(this.dropArea);
                }
                break;
        }
    },
    init : function () {
        document.addEventListener('touchstart', this.getTouch.bind(this), false);
        document.addEventListener('touchmove', this.getTouch.bind(this), false);
        document.addEventListener('touchend', this.getTouch.bind(this), false);
        window.onload = this.requestData('sentData.php');
    }
};

var drop = new Drop(document.getElementById('1'), document.getElementById('2'));
drop.init();
