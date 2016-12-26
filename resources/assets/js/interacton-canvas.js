function InteractionCanvas($el) { this.init($el) }

InteractionCanvas.prototype.init = function ($el) {
  this.$el = $el;
  this.endpoint = $el.data('endpoint');
  this.$interactionCounter = $($el.data('interactionCounter'));
  this.$customerCounter = $($el.data('customerCounter'));

  this.registerEvents();
  this.refreshPulse();
}

InteractionCanvas.prototype.updateCounter = function ($counter, value) {
  $counter.data('count', value);
  $counter.text($counter.data('count'));
}

InteractionCanvas.prototype.incrementCounter = function ($counter) {
  $counter.data('count', $counter.data('count') + 1);
  $counter.text($counter.data('count'));
}

InteractionCanvas.prototype.blinkInteractions = function (interactions) {
  var _this = this;

  Array.prototype.forEach.call(interactions, function(interaction, i){
    var delay = Math.random() * 61000;
    var position = [Math.random() * (90), Math.random() * (95)];
    var email = Object.keys(interaction)[0];
    setTimeout(function() {
      _this.incrementCounter(_this.$interactionCounter);
      $(
        '<div class="interaction" style="left:'+position[0]+'%; top:'+position[1]+'%;">'+email+
        '<br><span class="badge badge-primary">'+
        interaction[email]+'</span></div>'
      ).appendTo(_this.$el).fadeOut(4000, function() {this.remove(); })
    }, delay);
  });
}

InteractionCanvas.prototype.refreshPulse = function () {
  var _this = this;

  $.ajax({
    url: this.endpoint,
    method: 'get',
    dataType: "json",
    contentType: "application/json; charset=utf-8",
  }).done(function(response) {
    _this.blinkInteractions(response.content.interactionPulse);
    _this.updateCounter(_this.$customerCounter, response.content.customerCount)
  });;
}

InteractionCanvas.prototype.registerEvents = function () {
  var _this = this;

  setInterval(function() { _this.refreshPulse(); }, 60000);
}
