function PreviewQuery($el) { this.init($el); }

PreviewQuery.prototype.init = function ($el) {
  this.$el = $el;
  this.$queryBuilder = $($el.data('querybuilder'));
  this.$previewbox = $($el.data('previewbox'));
  this.endpoint = $el.data('endpoint');

  this.registerEvents();
}

PreviewQuery.prototype.registerEvents = function () {
  _this = this;

  this.$el.click(function(event) {
    var rules = _this.$queryBuilder.queryBuilder('getRules');

    _this.performRequest(rules);
    event.preventDefault();
  })
}

PreviewQuery.prototype.performRequest = function (rules) {
  var url = this.endpoint + '?query=' + JSON.stringify(rules);

  // $.ajax({
  //   url: url,
  //   contentType: {
  //     json: "application/json"
  //   }
  // });
  window.open(url, '_blank');
}
