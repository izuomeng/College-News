$.ajaxSetup({  
	    async : false  
}); 
//处理自动建议的类
function AutoComplete (textBox, provider) {
	 this.textBox = textBox;
	 this.ul = document.getElementById('ul1');
	 this.index = 0;
	 this.timeoutID = null;
	 this.init();
};
AutoComplete.prototype = {
	constructor : AutoComplete,
	//获取已经存在的文本，把建议（参数）放到文本框，选中建议中还没有输入的文本
	typeAhead : function (suggestion) {
		var nowText = this.textBox.value,
			length = nowText.length;
		this.textBox.value = suggestion;
		this.textBox.setSelectionRange(length, suggestion.length);

	},
	//用来显示建议下拉框，传入的是suggestions数组，第一行高亮并传入typeAhead
	showSuggestions : function (suggestions, isDelete) {
		if (suggestions.length > 0) {
			var fragment = document.createDocumentFragment(),
				li = null,
				ul = this.ul;
			for (var i = 0; i < suggestions.length; i++) {
				li = document.createElement('li');
				li.innerHTML = suggestions[i];
				li.className = 'list';
				li.id = i;
				fragment.appendChild(li);
			}
			fragment.firstElementChild.className = 'highlight';
			ul.innerHTML = '';
			ul.appendChild(fragment);
			ul.className = 'showlist';
			if (isDelete == false) {
				this.typeAhead(ul.firstElementChild.innerHTML);
			}
		}else {
			document.getElementById('ul1').className = 'hidelist';
		}
	},
	//当用户输入时获取输入，查询建议，将建议数组传给showSuggestion
	handleInput : function (isDelete) {
		var text = this.textBox.value,
			that = this;
		//函数截流
		clearTimeout(this.timeoutID);
		if (text.trim().length == 0) {
			this.ul.className = 'hidelist';
		}else {
			this.timeoutID = setTimeout(function () {
				var suggestions = provider.requestSuggestions(text, 10);
				that.showSuggestions(suggestions, isDelete);
			}, 250);
			
		}
	},
	//用户按上下按钮或者鼠标移动时可以选择不同的建议项
	selectSuggestion : function (event) {
		if (this.ul.children.length > 0) {
			switch (event.type) {
				case 'mouseover':
					if (event.target.tagName == 'LI') {
						this.ul.children[this.index].className = '';
						this.index = event.target.id;
						event.target.className = 'highlight';
					}
					break;
				case 'mouseout':
					break;
				case 'click':
					this.textBox.value = event.target.innerHTML;
					this.ul.className = 'hidelist';
					this.ul.innerHTML = '';
					this.index = 0;
					break;
				default:
					// statements_def
					break;
			}
		}
	},
	//键盘按键对应不同的处理程序
	keyboardSelect : function (event) {
		var li = this.ul.children;
		switch (event.keyCode) {
			case 8:
				this.handleInput(true);
				this.index = 0;
				break;
			case 38:
				if (this.index > 0 && li.length > 0) {
					li[this.index].className = 'list';
					li[--this.index].className = 'highlight';
					this.textBox.value = li[this.index].innerHTML;
				}
				break;
			case 40:
				if (this.index < li.length-1 && li.length > 0) {
					li[this.index].className = 'list';
					li[++this.index].className = 'highlight';
					this.textBox.value = li[this.index].innerHTML;
				}
				break;
			case 27:
			//esc和enter操作相同
			case 13:
				if (li.length > 0) {
					this.ul.className = 'hidelist';
					this.textBox.value = li[this.index].innerHTML;
					this.ul.innerHTML = '';
					this.index = 0;
				}
				break;
			case 37:
			case 39:
				break;
			default:
				this.handleInput(false);
				break;
		}
	},
	//初始化，把事件处理程序绑定到相应的元素
	init : function () {
		var that = this;
		this.textBox.addEventListener('keyup', that.keyboardSelect.bind(that))
		this.textBox.addEventListener('keydown', function (event) {
			if (event.keyCode == 38) {
				event.preventDefault();
			}
		})
		this.ul.addEventListener('mouseover', that.selectSuggestion.bind(that));
		this.ul.addEventListener('click', that.selectSuggestion.bind(that));
		
	}
}

//提供建议的类
function Provider () {
	
}
//传入字符串，在数据库搜索相应的匹配
Provider.prototype.requestSuggestions = function (text, maxLength) {
	var url = 'example.php',
		suggestions = [];
	$.get(url, {value: text, count: maxLength}, function (data) {
		trans = JSON.parse(data);
		suggestions = trans;
		if (suggestions[0] == null) {
			suggestions.shift();
		}
	});
	return suggestions;
}
var textBox = document.getElementById('2'),
	provider = new Provider(),
	suggest = new AutoComplete(textBox, provider);