function QueryBuilder($el) { this.init($el); }

QueryBuilder.prototype.init = function ($el) {
  console.log('lol');

  var rules_basic = {
    condition: 'AND',
    rules: [{
      id: 'price',
      operator: 'less',
      value: 10.25
    }, {
      condition: 'OR',
      rules: [{
        id: 'category',
        operator: 'equal',
        value: 2
      }, {
        id: 'category',
        operator: 'equal',
        value: 1
      }]
    }]
  };

  $el.queryBuilder({
    filters: [
      {
        id: 'interaction',
        label: 'Type of interaction',
        type: 'string',
        input: 'checkbox',
        values: {
          "searched": "Searched",
          "oppened-email": "Openned e-mail",
          "visited-category": "Visited category",
          "visited-content": "Visited content",
          "visited-product": "Visited product",
          "added-to-basket": "Added to basket",
          "purchased": "Purchased"
        },
        multiple: true,
        operators: ['equal']
      }, {
        id: 'productId',
        label: 'Product id',
        type: 'string',
        operators: ['equal']
      }, {
        id: 'category',
        label: 'Category',
        type: 'string',
        operators: ['equal']
      }, {
        id: 'term',
        label: 'Search term',
        type: 'string',
        operators: ['equal']
      }, {
        id: 'total',
        label: 'Total',
        type: 'double',
        validation: {
          min: 0,
          step: 10.00
        },
        operators: ['equal', 'greater_or_equal', 'less_or_equal']
      }, {
        id: 'price',
        label: 'Price',
        type: 'double',
        validation: {
          min: 0,
          step: 10.00
        },
        operators: ['equal', 'greater_or_equal', 'less_or_equal']
      }
    ],
    rules: rules_basic,
    lang: {
      conditions: {
        "AND": "And <i>(All)</i>",
        "OR": "Or <i>(At least one)</i>"
      }
    }
  });

  // To update styling of check and radioboxes
  $el.on('afterUpdateRuleValue.queryBuilder', function () {
    $('input[type=checkbox],input[type=radio]').each(function () {
      if (this.checked) {
        $(this.parentElement).addClass('active');
        return;
      }

      $(this.parentElement).removeClass('active');
    })
  })
}
