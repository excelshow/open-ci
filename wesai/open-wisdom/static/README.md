static(项目名称)  

|–node_modules 组件目录

|–build 发布环境

    |–projectname1/
    
       |–css/xxx.css 样式文件(style.css style.min.css)
    
       |–img/xxx.png 图片文件(压缩图片)
    
       |–js/xxx.js  js文件(main.js main.min.js)
    
       |-lib js文件库 
    
       |–index.html 静态文件(保护目录html)
    |–projectname2/
       同上
    
|–client 生产环境（gulp自动化发布）

    |–sass/projectname/xxx.scss 文件
    
    |-sass/reset.scss
    
    |-js/projectname/xxx.js
    
    |–index.html 静态文件
    
|–gulpfile.js gulp任务文件

|-package.json