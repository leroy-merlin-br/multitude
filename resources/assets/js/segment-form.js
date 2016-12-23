function SegmentForm($el) { var _this = this; setTimeout(function () {
  _this.init($el);
}, 500); }

SegmentForm.prototype.init = function ($el) {
  this.$el = $el;
  this.$queryBuilder = $el.find('[data-module=QueryBuilder]');

  this.registerEvents();
}

SegmentForm.prototype.registerEvents = function () {
  var _this = this;

  this.$el.find('[data-xd]').click(function (event) {
    _this.submit(_this.getJsonRepresentation());
    event.preventDefault();
  });
}

SegmentForm.prototype.getJsonRepresentation = function () {
  return {
    name: this.$el.find('[name=name]').val(),
    slug: this.$el.find('[name=slug]').val(),
    additionInterval: this.$el.find('[name=additionInterval]').val(),
    removalInterval: this.$el.find('[name=removalInterval]').val(),
    ruleset: {
      rules: this.$queryBuilder.queryBuilder('getRules')
    }
  };
}

SegmentForm.prototype.submit = function (data) {
  var action = this.$el.attr("action");
  var method = this.$el.attr("method") || "get";

  $.ajax({
    url: action,
    method: method,
    data: JSON.stringify(data),
    dataType: "json",
    contentType: "application/json; charset=utf-8",
  });
}
