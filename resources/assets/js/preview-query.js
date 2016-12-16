function PreviewQuery($el) { this.init($el); }

PreviewQuery.prototype.init = function ($el) {
  this.$el = $el;
  this.$queryBuilder = $($el.data('querybuilder'));
  this.$previewbox = $($el.data('previewbox'));

  this.registerEvents();
}

PreviewQuery.prototype.registerEvents = function () {
  _this = this;

  this.$el.click(function() {
    var rules = _this.$queryBuilder.queryBuilder('getRules');

    _this.performRequest(rules);
  })
}

PreviewQuery.prototype.performRequest = function (rules) {
  var url = 'api/v1/customer?query=' + JSON.stringify(rules);

  // $.ajax({
  //   url: url,
  //   contentType: {
  //     json: "application/json"
  //   }
  // });
  window.open(url, '_blank');
}
