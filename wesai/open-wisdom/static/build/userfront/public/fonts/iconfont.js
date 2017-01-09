;(function(window) {

var svgSprite = '<svg>' +
  ''+
    '<symbol id="icon-zhuye" viewBox="0 0 1000 1000">'+
      ''+
      '<path d="M939.3746 453.2357 542.9647 56.8425c-7.9-7.9006-18.2784-11.804-28.6318-11.7231-10.3534-0.0809-20.7318 3.8224-28.6308 11.7231L89.2922 453.2357c-15.6391 15.6384-15.6391 40.9862 0 56.6246 7.804 7.8027 18.0265 11.7101 28.255 11.7241v0.13490853201529363h39.678764305901446c21.9141 0 39.6788 17.771 39.6788 39.6831v277.73569519865526c0 43.8293 35.5304 79.3532 79.3585 79.3532h119.03329482449162c43.8291 0 79.3585-35.5249 79.3585-79.3532V720.1057682891001c0-21.9051 17.7637-39.6711 39.6768-39.6711 21.9141 0 39.6788 17.765 39.6788 39.6711v119.032296103242c0 43.8293 35.5304 79.3532 79.3565 79.3532h119.03529355330011c43.8271 0 79.3565-35.5249 79.3565-79.3532V561.4023691936867c0-21.9111 17.7637-39.6831 39.6788-39.6831h39.678764305901446v-0.13490853201529363c10.2285-0.015 20.453-3.9223 28.257-11.7251C955.0136 494.2219 955.0136 468.8741 939.3746 453.2357zM752.4035 521.7193v277.7436897783303c0 21.9151-17.7637 39.6751-39.6788 39.6751h-39.678764305901446c-21.9121 0-39.6788-17.759-39.6788-39.6751V680.4346652969286c0-43.8293-35.5304-79.3572-79.3565-79.3572h-79.35453051859018c-43.8291 0-79.3585 35.5289-79.3585 79.3572v119.0282988134045c0 21.9151-17.7637 39.6751-39.6788 39.6751h-39.678764305901446c-21.9121 0-39.6768-17.759-39.6768-39.6751V521.7192743320029c0-38.6658-27.6734-70.863-64.2951-77.9032L514.3339 141.4641l302.3647 302.352C780.0769 450.8563 752.4035 483.0535 752.4035 521.7193z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-wode" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M955.923532 922.936233c1.231037 4.084015 2.091638 8.329712 2.091638 12.81691 0 24.64019-19.951401 44.602847-44.570101 44.602847-24.615631 0-44.567032-19.962657-44.567032-44.602847 0-0.049119 0.013303-0.094144 0.013303-0.143263l-1.166569 0c-11.606339-186.693006-166.216649-334.626243-355.781052-334.626243-189.568497 0-344.178807 147.933237-355.785146 334.626243l-1.055029 0c0.002047 0.049119 0.01535 0.094144 0.01535 0.143263 0 24.64019-19.952424 44.602847-44.570101 44.602847-24.615631 0-44.567032-19.962657-44.567032-44.602847 0-4.226255 0.775666-8.234545 1.873674-12.110829C80.799234 754.241137 187.807387 611.205443 337.194742 547.482119c-69.481468-50.685379-114.818025-132.506896-114.818025-225.116107 0-153.925718 124.738979-278.723026 278.622742-278.723026 153.874553 0 278.620695 124.796284 278.620695 278.723026 0 89.328491-42.174542 168.638815-107.507526 219.645512C828.733735 602.420359 942.290023 748.498342 955.923532 922.936233zM690.305989 322.366012c0-104.572684-84.903715-189.338253-189.632965-189.338253-104.737436 0-189.637058 84.765569-189.637058 189.338253 0 104.563474 84.899622 189.336206 189.637058 189.336206C605.402273 511.702218 690.305989 426.929486 690.305989 322.366012z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-youhuiquan" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M768.487 285.47h-512.963c-13.352 0-23.891 10.189-23.891 22.481v103.313c42.51 17.216 72.37 56.56 72.37 102.23s-29.86 85.016-72.37 102.23v103.313c0 12.296 10.538 22.485 23.891 22.485h145.090c0 0 5.266-0.002 11.941-0.002l24.227 0.148c6.673 0 11.961-0.146 11.961-0.146h319.745c13.351 0 24.242-10.189 24.242-22.485v-103.313c-42.861-17.213-72.721-56.56-72.721-102.23s29.86-85.016 72.721-102.23v-103.313c0-12.296-10.891-22.481-24.242-22.481zM620.856 450.014c9.939 0 17.998 8.058 17.998 17.995 0 9.938-8.058 17.998-17.998 17.998h-90.855v61.027h90.855c9.939 0 17.998 8.055 17.998 17.995s-8.058 17.998-17.998 17.998h-90.855v102.692c0 9.939-8.058 17.998-17.998 17.998s-17.995-8.058-17.995-17.998v-102.692h-90.859c-9.939 0-17.998-8.058-17.998-17.998 0-9.938 8.058-17.995 17.998-17.995h90.859v-61.027h-90.859c-9.939 0-17.998-8.058-17.998-17.998 0-9.936 8.058-17.995 17.998-17.995h65.408l-78.133-78.133c-7.028-7.030-7.028-18.423 0-25.451 7.030-7.027 18.422-7.027 25.45 0l96.124 96.128 96.131-96.128c7.027-7.027 18.422-7.027 25.448 0 7.030 7.030 7.030 18.423 0 25.451l-78.133 78.133h65.406z"  ></path>'+
      ''+
      '<path d="M512.003 3.167c-281.021 0-508.833 227.813-508.833 508.833s227.813 508.833 508.833 508.833 508.833-227.813 508.833-508.833-227.814-508.833-508.833-508.833zM840.855 352.569v93.824c-40.049 0-72.369 30.211-72.369 67.099s32.322 67.099 72.369 67.099v160.928c0 24.594-21.779 44.618-48.128 44.618h-561.095c-26.699 0-48.479-20.025-48.479-44.618v-160.924c40.049 0 72.37-30.216 72.37-67.103 0-36.883-32.323-67.099-72.37-67.099v-160.927c0-24.589 21.785-44.613 48.479-44.613h561.095c26.349 0 48.128 20.026 48.128 44.613v67.103z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-tuanduicheng" viewBox="0 0 1025 1024">'+
      ''+
      '<path d="M512.587879 0c-282.769794 0-512 229.221686-512 512s229.221686 512 512 512 512-229.221686 512-512S795.366193 0 512.587879 0zM277.981562 570.08074c-6.245149 10.241022-9.389024 21.615203-9.389024 34.114021l0 96.693314-77.191067 0c-5.495391 0-9.976903-2.121476-13.478617-6.381469-3.501714-4.242953-5.248311-9.610543-5.248311-16.102772L172.674543 586.933271c0-10.990781 3.987353-17.985689 11.979099-20.967684l116.18704-53.974107c-13.998336-8.494425-25.244717-20.609845-33.739142-36.354778-8.502945-15.744933-12.737378-33.355743-12.737378-52.840949 0-13.487137 2.249276-26.360835 6.747828-38.595534 4.507072-12.243219 10.624422-22.867641 18.352048-31.856225 7.753187-8.997105 16.741771-16.111292 26.999834-21.359603 10.232502-5.248311 21.342563-7.863946 33.338703-7.863946 8.000266 0 15.497853 1.243918 22.484241 3.731754 7.003428 2.496356 13.751256 5.75099 20.234965 9.746863-8.477385 20.984724-12.728858 42.727727-12.728858 65.203448 0 17.50005 2.487836 34.352581 7.489067 50.591673 4.975671 16.239092 11.979099 30.850867 20.984724 43.843845-5.503911 5.99807-11.246381 10.735182-17.25297 14.236896l-77.191067 36.738177C292.848937 552.205811 284.235231 559.831198 277.981562 570.08074zM719.061204 700.879555l0 17.21889c0 7.506107-2.232236 14.006856-6.739308 19.502246-4.490032 5.503911-10.223982 8.238826-17.22741 8.238826L327.073718 745.839518c-6.492229 0-11.979099-2.734915-16.486172-8.238826-4.498552-5.503911-6.747828-11.996139-6.747828-19.502246l0-17.21889L303.839718 604.194762c0-11.996139 4.992711-20.482045 14.986654-25.491796l103.432622-47.967518 40.46993-18.735448c-18.999567-11.49346-33.475022-27.979632-43.468965-49.475555-9.499784-18.991047-14.253936-39.217493-14.253936-60.713416 0-6.500749 0.511199-12.737378 1.508037-18.726928 1.005358-5.98955 2.249276-11.74906 3.731754-17.24445 7.003428-25.483276 19.630046-46.45948 37.854295-62.954172 18.258329-16.494692 39.362332-24.742037 63.337571-24.742037 24.494958 0 45.982361 8.502945 64.445169 25.474756 18.496888 17.005891 30.995707 38.493294 37.487936 64.470729 2.496356 12.490299 3.740274 23.736679 3.740274 33.722102 0 19.987886-4.004393 39.226013-11.979099 57.714381-9.993943 22.492761-24.486438 39.481612-43.486005 50.958032l42.736247 20.243485 99.692349 47.967518c9.976903 5.009751 14.969614 13.495657 14.969614 25.491796L719.044164 700.879555zM852.501215 678.403834c0 6.492229-1.772157 11.85982-5.248311 16.102772-3.493194 4.251473-7.991746 6.372949-13.487137 6.372949l-79.457383 0L754.308384 604.194762c0-12.498819-3.109795-23.872999-9.363464-34.114021-6.262189-10.258062-14.620295-17.874929-25.099877-22.859121l-74.209072-35.230139c-6.994908-3.995873-13.742736-9.746863-20.243485-17.24445 8.485905-12.975938 15.114454-27.357673 19.868606-43.094086 4.745632-15.744933 7.122708-32.350384 7.122708-49.850434 0-11.49346-1.005358-22.484241-3.007555-32.980863-1.993677-10.479582-4.992711-20.737644-8.988585-30.714547 6.492229-4.507072 13.487137-8.119546 20.984724-10.880021 7.480547-2.743435 15.233734-4.098113 23.234-4.098113 12.013179 0 23.234 2.615636 33.722102 7.863946 10.496622 5.239791 19.638566 12.362499 27.374713 21.359603s13.844976 19.621526 18.352048 31.856225c4.490032 12.243219 6.747828 25.108397 6.747828 38.595534 0 18.982527-4.251473 36.346258-12.728858 52.09119-8.511465 15.736413-19.238127 27.851832-32.248145 36.346258l114.687523 54.732386c7.983226 3.995873 11.987619 10.982261 11.987619 20.967684L852.501215 678.403834z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-dingdan" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M512 10.44898c-277.002449 0-501.55102 224.548571-501.55102 501.55102s224.548571 501.55102 501.55102 501.55102c277.002449 0 501.55102-224.548571 501.55102-501.55102S789.002449 10.44898 512 10.44898zM451.960163 259.322776l120.832 0c19.163429 0 34.398041 17.470694 34.398041 37.908898 0 20.291918-15.067429 37.908898-33.645714 37.908898l-121.584327 0c-19.163429 0-34.398041-17.470694-34.398041-37.908898C417.562122 276.772571 432.796735 259.322776 451.960163 259.322776zM643.197388 561.277388 381.534041 561.277388c-13.876245 0-25.49551-11.535673-25.49551-25.39102 0-13.834449 11.619265-25.39102 25.49551-25.39102l260.931918 0c13.834449 0 25.202939 10.657959 26.226939 25.39102C668.692898 549.741714 657.094531 561.277388 643.197388 561.277388zM668.692898 639.770122c0 13.834449-11.619265 25.370122-25.49551 25.370122L381.534041 665.140245c-13.876245 0-25.49551-11.535673-25.49551-25.370122 0-13.834449 11.619265-25.39102 25.49551-25.39102l260.931918 0C656.321306 614.4 667.668898 625.037061 668.692898 639.770122zM643.197388 465.522939 381.534041 465.522939c-13.876245 0-25.49551-11.535673-25.49551-25.370122 0-13.834449 11.619265-25.39102 25.49551-25.39102l261.684245 0c13.876245 0 25.49551 11.535673 25.49551 25.39102C668.692898 453.987265 657.094531 465.522939 643.197388 465.522939zM781.374694 748.063347c0 51.889633-29.152653 80.624327-81.084082 80.624327L323.709388 828.687673c-50.196898 0-81.084082-32.078367-81.084082-80.624327L242.625306 353.948735c0-47.480163 27.271837-77.677714 77.385143-77.677714l43.739429 0c13.813551 0 21.044245 9.048816 21.044245 23.175837 0 14.942041-7.06351 26.853878-21.044245 26.853878l-43.739429 0c-16.509388 0-27.146449 10.553469-27.146449 26.916571l0 398.54498c0 14.377796 17.219918 26.916571 36.028082 26.916571l375.097469 0c16.509388 0 27.146449-10.553469 27.146449-26.916571L731.136 353.217306c0-16.363102-10.616163-26.916571-27.146449-26.916571L669.152653 326.300735c-13.980735 0-28.46302-13.061224-28.46302-26.853878 0-13.855347 13.897143-23.175837 28.46302-23.175837l34.836898 0c47.626449 0 77.385143 31.681306 77.385143 77.677714L781.374694 748.063347z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-changguanguanli" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M899.160064 801.155072c-91.136 108.251136-225.03424 165.459968-387.155968 165.459968-162.154496 0-296.02816-57.208832-387.145728-165.459968C44.343296 705.519616 0 571.731968 0 424.47872 0 139.114496 265.41056 9.152512 512.004096 9.152512 758.587392 9.152512 1024 139.114496 1024 424.47872 1024 571.731968 979.658752 705.519616 899.160064 801.155072L899.160064 801.155072 899.160064 801.155072zM512.004096 53.426176c-225.150976 0-467.48672 116.113408-467.48672 371.052544 0 229.658624 122.437632 497.881088 467.48672 497.881088 345.040896 0 467.478528-268.224512 467.478528-497.881088C979.482624 169.539584 737.171456 53.426176 512.004096 53.426176L512.004096 53.426176 512.004096 53.426176zM512.004096 592.187392c-217.40544 0-387.725312-105.224192-387.725312-239.593472 0-134.346752 170.319872-239.59552 387.725312-239.59552 217.37472 0 387.694592 105.250816 387.694592 239.59552C899.698688 486.9632 729.378816 592.187392 512.004096 592.187392L512.004096 592.187392 512.004096 592.187392zM512.004096 157.278208c-186.036224 0-343.197696 89.444352-343.197696 195.315712 0 105.869312 157.161472 195.31776 343.197696 195.31776 186.005504 0 343.169024-89.448448 343.169024-195.31776C855.17312 246.72256 698.0096 157.278208 512.004096 157.278208L512.004096 157.278208 512.004096 157.278208zM213.21728 638.53568c-5.25312-3.16416-6.97344-9.947136-3.807232-15.179776 3.227648-5.23264 10.102784-6.899712 15.292416-3.784704 25.153536 15.067136 50.417664 27.828224 75.819008 38.711296l0-42.45504c0-6.115328 4.98688-11.063296 11.134976-11.063296 6.144 0 11.128832 4.943872 11.128832 11.063296l0 51.556352c58.499072 22.2208 117.733376 33.615872 178.089984 33.951744l0-56.899584c0-6.119424 4.960256-11.083776 11.132928-11.083776 6.137856 0 11.13088 4.964352 11.13088 11.083776l0 56.32c58.210304-2.248704 117.469184-14.647296 178.087936-36.995072l0-47.931392c0-6.115328 4.980736-11.063296 11.128832-11.063296 6.144 0 11.134976 4.943872 11.134976 11.063296l0 39.335936c25.171968-10.26048 50.573312-22.132736 76.263424-35.864576 5.453824-2.8672 12.152832-0.868352 15.067136 4.499456 2.916352 5.388288 0.913408 12.107776-4.51584 15.003648-29.093888 15.57504-58.036224 28.667904-86.816768 39.86432l0 51.87584c25.219072-10.268672 50.618368-22.112256 76.263424-35.91168 5.339136-2.871296 12.152832-0.935936 15.048704 4.472832 2.936832 5.388288 0.933888 12.115968-4.49536 15.007744-29.054976 15.628288-57.985024 28.760064-86.816768 39.936l0 62.599168c0 6.119424-4.990976 11.07968-11.134976 11.07968-6.141952 0-11.128832-4.960256-11.128832-11.07968l0-54.124544c-59.992064 20.975616-119.410688 32.352256-178.087936 34.240512l0 70.61504c0 6.115328-4.993024 11.07968-11.13088 11.07968-6.166528 0-11.132928-4.964352-11.132928-11.07968l0-70.172672c-60.2112-0.536576-119.605248-11.239424-178.089984-32.008192l0 51.447808c0 6.119424-4.982784 11.07968-11.128832 11.07968-6.144 0-11.134976-4.960256-11.134976-11.07968l0-60.08832c-29.31712-11.732992-58.40896-25.755648-87.21408-42.647552-5.29408-3.115008-7.061504-9.887744-3.9424-15.159296 3.160064-5.275648 10.016768-6.991872 15.251456-3.919872 25.219072 14.823424 50.532352 27.379712 75.905024 38.131712l0-51.023872C271.11424 670.011392 241.997824 655.763456 213.21728 638.53568L213.21728 638.53568 213.21728 638.53568zM523.134976 775.548928c58.433536-2.019328 117.690368-14.22336 178.087936-36.415488l0-51.955712c-60.192768 21.123072-119.609344 32.679936-178.087936 34.79552L523.134976 775.548928 523.134976 775.548928 523.134976 775.548928zM322.783232 741.9392c58.742784 22.079488 118.007808 33.499136 178.089984 34.076672l0-53.467136c-60.438528-0.331776-119.834624-11.016192-178.089984-31.895552L322.783232 741.9392 322.783232 741.9392 322.783232 741.9392zM322.783232 741.9392"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-wodedingdan" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M554.666667 650.666667l-149.333333 0c-12.8 0-21.333333 8.533333-21.333333 21.333333 0 12.8 8.533333 21.333333 21.333333 21.333333l149.333333 0c12.8 0 21.333333-8.533333 21.333333-21.333333C576 659.2 567.466667 650.666667 554.666667 650.666667z"  ></path>'+
      ''+
      '<path d="M618.666667 544 405.333333 544c-12.8 0-21.333333 8.533333-21.333333 21.333333 0 12.8 8.533333 21.333333 21.333333 21.333333l213.333333 0c12.8 0 21.333333-8.533333 21.333333-21.333333C640 552.533333 631.466667 544 618.666667 544z"  ></path>'+
      ''+
      '<path d="M512 0C228.266667 0 0 228.266667 0 512s228.266667 512 512 512 512-228.266667 512-512S795.733333 0 512 0zM448 288l128 0c12.8 0 21.333333 8.533333 21.333333 21.333333 0 12.8-8.533333 21.333333-21.333333 21.333333l-128 0c-12.8 0-21.333333-8.533333-21.333333-21.333333C426.666667 296.533333 435.2 288 448 288zM725.333333 736c0 23.466667-19.2 42.666667-42.666667 42.666667L341.333333 778.666667c-23.466667 0-42.666667-19.2-42.666667-42.666667l0-384c0-23.466667 19.2-42.666667 42.666667-42.666667l42.666667 0c0 23.466667 19.2 42.666667 42.666667 42.666667l170.666667 0c23.466667 0 42.666667-19.2 42.666667-42.666667l42.666667 0c23.466667 0 42.666667 19.2 42.666667 42.666667L725.333333 736z"  ></path>'+
      ''+
      '<path d="M618.666667 437.333333 405.333333 437.333333c-12.8 0-21.333333 8.533333-21.333333 21.333333 0 12.8 8.533333 21.333333 21.333333 21.333333l213.333333 0c12.8 0 21.333333-8.533333 21.333333-21.333333C640 445.866667 631.466667 437.333333 618.666667 437.333333z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-wodezu" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M512.007675 62.72867c-247.378134 0-447.918519 200.524013-447.918519 447.901123 0 247.369947 200.541409 447.910333 447.918519 447.910333 247.361761 0 447.902146-200.541409 447.902146-447.910333C959.909821 263.251659 759.368412 62.72867 512.007675 62.72867zM620.115881 310.998103c3.061732 0 6.036483 0.245593 8.975419 0.630357 2.957355-0.384763 5.931083-0.630357 8.992815-0.630357 60.817134 0 90.980107 79.32772 72.311933 145.847739-14.189164 50.54621-49.479925 78.155011-81.304747 79.939657-14.417361-0.804319-29.498848-7.208169-43.057655-18.370393 6.420223-11.110035 12.054547-23.304775 16.148795-37.144992 16.200984-54.482869 5.02034-115.579365-26.76969-152.303778C587.450924 317.822532 602.44543 310.998103 620.115881 310.998103zM450.124256 295.637254c4.111644 0 8.135284 0.315178 12.106736 0.822738 3.954055-0.50756 7.978718-0.822738 12.107759-0.822738 81.969896 0 122.595152 100.970646 97.452519 185.633884-19.123546 64.333214-66.677609 99.465362-109.560278 101.740172-42.901089-2.27481-90.455151-37.406958-109.560278-101.740172C327.510684 396.606877 368.136963 295.637254 450.124256 295.637254zM477.505883 725.631542l-33.120328 0c-219.051995 0-201.206558-43.25106-201.206558-43.25106-7.646144-89.055269 109.526509-132.314515 109.526509-132.314515 30.635741 65.934688 98.31005 68.663846 108.2484 68.698638 9.93835-0.034792 77.612659-2.76395 108.2484-68.698638 0 0 117.17163 43.258223 109.525486 132.314515C678.728814 682.380482 696.556854 725.631542 477.505883 725.631542zM705.025737 646.444015c-12.247952-39.331797-48.324612-66.407456-74.988902-81.610716 11.704576-0.455371 57.143465-5.389754 78.435397-53.92312 0 0 86.938048 33.976836 81.269955 103.935164C789.742187 614.845343 798.665417 638.815267 705.025737 646.444015z"  ></path>'+
      ''+
    '</symbol>'+
  ''+
    '<symbol id="icon-changguanguanli-copy" viewBox="0 0 1024 1024">'+
      ''+
      '<path d="M799.414 726.658c-67.657 80.362-167.057 122.831-287.411 122.831-120.377 0-219.76-42.47-287.402-122.831-59.77-70.995-92.69-170.316-92.69-279.63 0-211.843 197.031-308.324 380.093-308.324 183.054 0 380.087 96.48 380.087 308.324 0 109.315-32.917 208.635-92.677 279.63v0 0zM512.004 171.571c-167.144 0-347.044 86.198-347.044 275.457 0 170.491 90.893 369.608 347.045 369.608 256.145 0 347.038-199.121 347.038-369.608 0-189.257-179.883-275.457-347.038-275.457v0 0zM512.004 571.528c-161.393 0-287.833-78.114-287.833-177.866 0-99.734 126.439-177.867 287.833-177.867 161.372 0 287.81 78.134 287.81 177.867 0 99.75-126.439 177.866-287.81 177.866v0 0zM512.004 248.666c-138.106 0-254.778 66.4-254.778 144.996 0 78.594 116.671 144.998 254.778 144.998 138.084 0 254.756-66.402 254.756-144.998 0-78.594-116.672-144.996-254.756-144.996v0 0zM290.195 605.936c-3.899-2.349-5.176-7.384-2.825-11.269 2.397-3.885 7.501-5.123 11.352-2.81 18.674 11.185 37.429 20.659 56.285 28.738v-31.517c0-4.54 3.702-8.212 8.267-8.212 4.562 0 8.262 3.67 8.262 8.212v38.273c43.428 16.497 87.4 24.956 132.208 25.204v-42.24c0-4.543 3.682-8.228 8.265-8.228 4.556 0 8.264 3.686 8.264 8.228v41.81c43.213-1.669 87.204-10.874 132.206-27.464v-35.582c0-4.54 3.696-8.212 8.262-8.212 4.562 0 8.267 3.67 8.267 8.212v29.201c18.687-7.616 37.545-16.431 56.614-26.625 4.048-2.128 9.023-0.645 11.185 3.34 2.165 4 0.678 8.988-3.352 11.14-21.598 11.562-43.084 21.282-64.45 29.593v38.513c18.722-7.623 37.577-16.415 56.615-26.66 3.964-2.13 9.023-0.695 11.171 3.321 2.181 4 0.694 8.994-3.337 11.142-21.568 11.603-43.046 21.35-64.45 29.646v46.471c0 4.543-3.706 8.226-8.267 8.226-4.56 0-8.262-3.682-8.262-8.226v-40.181c-44.535 15.572-88.647 24.017-132.206 25.42v52.422c0 4.54-3.708 8.226-8.264 8.226-4.578 0-8.265-3.686-8.265-8.226v-52.095c-44.699-0.399-88.791-8.343-132.208-23.761v38.192c0 4.543-3.698 8.226-8.262 8.226-4.562 0-8.267-3.682-8.267-8.226v-44.607c-21.764-8.711-43.361-19.121-64.745-31.66-3.93-2.313-5.243-7.341-2.926-11.253 2.346-3.917 7.438-5.19 11.322-2.91 18.722 11.004 37.513 20.326 56.348 28.308v-37.879c-21.831-8.856-43.445-19.43-64.809-32.221v0 0zM520.267 707.65c43.379-1.499 87.368-10.558 132.206-27.034v-38.57c-44.686 15.682-88.793 24.259-132.206 25.831v39.774zM371.533 682.698c43.609 16.389 87.604 24.869 132.208 25.296v-39.691c-44.868-0.246-88.961-8.177-132.208-23.678v38.073zM371.533 682.698z"  ></path>'+
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