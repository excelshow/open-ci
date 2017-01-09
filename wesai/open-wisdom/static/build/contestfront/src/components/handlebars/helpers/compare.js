module.exports = function (p1, operator, p2, options) {
	if (eval(p1 + operator + p2)) {
		return options.fn(this);
	} else {
		return options.inverse(this);
	}
};
