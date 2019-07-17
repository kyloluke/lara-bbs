<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>广播测试</title>

</head>
<body>
<div>
    <ul id="message">
    </ul>
</div>

<script src="{{ mix('js/app.js') }}"></script>
<script>
    $(document).ready(function () {
        let message = document.getElementById('message')
        Echo.channel('news').listen('News', (e) => {
            console.log(e.message)
            let msg = e.message
            // 创建节点
            let li = document.createElement('li');
            // 给节点填充内容
            li.innerHTML = `用户：<span style="color:red">${msg.user.name}</span> 给
                            用户：<span style="color:blue">${msg.topic.user.name}</span> 的
                            文章：<span style="color:purple">${msg.topic.title}</span> 留言：
                                   <span style="color:green">${msg.content}</span>`

            message.appendChild(li)
        });
    })

</script>
</body>
</html>