var Translate = function () {
    this.translations = {};
    this.set = function (index, value) {
        this.translations[index] = value;
    };
    this.get = function (index) {
        return this.translations[index];
    }
};