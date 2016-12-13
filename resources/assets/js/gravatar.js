function Gravatar($el) { this.init($el) }

Gravatar.prototype.init = function ($el) {
  var none = $el.data('default');
  var size = $el.data('size') || '60';

  $el.attr(
    'src',
    "https://www.gravatar.com/avatar/"+md5($el.data('email'))+"?d="+encodeURI(none)+"&s="+size
  );

  console.log("https://www.gravatar.com/avatar/"+md5($el.data('email'))+"?d="+encodeURI(none)+"&s="+size);
}
