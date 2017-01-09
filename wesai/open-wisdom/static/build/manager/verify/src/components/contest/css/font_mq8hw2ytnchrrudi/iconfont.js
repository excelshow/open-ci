;(function(window) {

var svgSprite = '<svg>' +
  ''+
    '<symbol id="icon-iconziti56" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M511.423367 954.414155c-246.442832 0-446.225971-199.783139-446.225971-446.225971 0-246.443855 199.782116-446.225971 446.225971-446.225971s446.225971 199.781092 446.225971 446.225971C957.648314 754.629993 757.866199 954.414155 511.423367 954.414155zM716.032418 378.059452c8.379854-8.378831 8.379854-21.986757 0-30.367635l-45.555545-45.555545c-8.378831-8.414647-21.988804-8.414647-30.369681 0L511.019161 431.224302l-129.085984-129.087007c-8.379854-8.414647-21.988804-8.414647-30.369681 0l-45.553499 45.555545c-8.381901 8.379854-8.381901 21.98778 0 30.367635l129.052215 129.122823-129.052215 129.053238c-8.381901 8.380878-8.381901 21.989827 0 30.369681l45.553499 45.590338c8.379854 8.379854 21.988804 8.379854 30.369681 0l129.085984-129.122823 129.08803 129.122823c8.379854 8.379854 21.989827 8.379854 30.369681 0l45.555545-45.590338c8.379854-8.378831 8.379854-21.98778 0-30.369681l-129.055285-129.053238L716.032418 378.059452z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-iconziti55" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M512.363785 960.828238c-247.530606 0-448.189695-200.666252-448.189695-448.192765 0-247.52856 200.660112-448.189695 448.189695-448.189695 247.52856 0 448.188672 200.662159 448.188672 448.189695C960.552457 760.161986 759.891322 960.828238 512.363785 960.828238zM762.517125 355.575368l-44.576241-44.612057c-8.214079-8.215102-21.515013-8.215102-29.732162 0l-267.38991 267.394003-127.201054-128.107703c-8.216125-8.214079-21.51092-8.214079-29.726022 0l-44.578288 44.577265c-8.215102 8.213056-8.215102 21.512966 0 29.729092l141.404544 142.482085c0.202615 0.201591 0.271176 0.473791 0.474814 0.672312l30.600949 30.606065 7.372921 7.440459 0-0.031722 6.600325 6.595208c8.217149 8.184403 21.549805 8.184403 29.764907 0l326.984193-327.015915C770.729158 377.088334 770.729158 363.755678 762.517125 355.575368z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
'</svg>'
var script = function() {
    var scripts = document.getElementsByTagName('script')
    return scripts[scripts.length - 1]
  }()
var shouldInjectCss = script.getAttribute("data-injectcss")

/**
 * document ready
 */
var ready = function(fn){
  if(document.addEventListener){
      document.addEventListener("DOMContentLoaded",function(){
          document.removeEventListener("DOMContentLoaded",arguments.callee,false)
          fn()
      },false)
  }else if(document.attachEvent){
     IEContentLoaded (window, fn)
  }

  function IEContentLoaded (w, fn) {
      var d = w.document, done = false,
      // only fire once
      init = function () {
          if (!done) {
              done = true
              fn()
          }
      }
      // polling for no errors
      ;(function () {
          try {
              // throws errors until after ondocumentready
              d.documentElement.doScroll('left')
          } catch (e) {
              setTimeout(arguments.callee, 50)
              return
          }
          // no errors, fire

          init()
      })()
      // trying to always fire before onload
      d.onreadystatechange = function() {
          if (d.readyState == 'complete') {
              d.onreadystatechange = null
              init()
          }
      }
  }
}

/**
 * Insert el before target
 *
 * @param {Element} el
 * @param {Element} target
 */

var before = function (el, target) {
  target.parentNode.insertBefore(el, target)
}

/**
 * Prepend el to target
 *
 * @param {Element} el
 * @param {Element} target
 */

var prepend = function (el, target) {
  if (target.firstChild) {
    before(el, target.firstChild)
  } else {
    target.appendChild(el)
  }
}

function appendSvg(){
  var div,svg

  div = document.createElement('div')
  div.innerHTML = svgSprite
  svg = div.getElementsByTagName('svg')[0]
  if (svg) {
    svg.setAttribute('aria-hidden', 'true')
    svg.style.position = 'absolute'
    svg.style.width = 0
    svg.style.height = 0
    svg.style.overflow = 'hidden'
    prepend(svg,document.body)
  }
}

if(shouldInjectCss && !window.__iconfont__svg__cssinject__){
  window.__iconfont__svg__cssinject__ = true
  try{
    document.write("<style>.svgfont {display: inline-block;width: 1em;height: 1em;fill: currentColor;vertical-align: -0.1em;font-size:16px;}</style>");
  }catch(e){
    console && console.log(e)
  }
}

ready(appendSvg)


})(window)
