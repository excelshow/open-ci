function getConfig (name) {
    var config = {
        "test" : {
            color : {
                size : 64
            }
        }
    };

    // 进行clone操作
    function clone(data) {
        var dataType = typeof(data), rebuildData, dataLength, i;
        if (dataType === 'array') {
            rebuildData = [];
        } else if (dataType === 'object') {
            rebuildData = {};
        } else {
            return data;
        }

        // 判断数组和对象进行clone
        if (dataType === 'array') {
            for (var i = 0, dataLength = data.length; i < dataLength; i++) {
                rebuildData.push(clone(data[i]));
            };
        } else if (dataType === 'object') {
            for (i in data) {
                rebuildData[i] = clone(data[i]);
            }
        }

        return rebuildData;
    }

    // 将配置数据进行克隆
    return clone(config[name]);
}
