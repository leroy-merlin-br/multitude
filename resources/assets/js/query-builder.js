function QueryBuilder($el) { this.init($el); }

/**
 * Gets the currentQuery
 * @return {object}           Json object describing jquery-Query-Builde rules.
 */
QueryBuilder.prototype.getCurrentQuery = function () {
  if (typeof window.localStorage !== 'undefined') {
    var inProgress = window.localStorage.queryInProgress ? JSON.parse(window.localStorage.queryInProgress) : null;

    if (inProgress && ! $.isEmptyObject(inProgress)) {
      return inProgress;
    }
  }

  return {
    condition: 'AND',
    rules: [{
      id: 'interaction',
      operator: 'in',
      value: []
    }]
  };
}

QueryBuilder.prototype.autoSaveCurrentQuery = function (query) {
  if (typeof window.localStorage !== 'undefined') {
    window.localStorage.queryInProgress = JSON.stringify(query);
  }
}

QueryBuilder.prototype.init = function ($el) {
  var currentQuery = this.getCurrentQuery();
  this.initializeQueryBuilder($el, currentQuery);
  this.registerEvents($el);
  this.refreshCheckboxVisuals($el);
}

QueryBuilder.prototype.registerEvents = function ($el) {
  var _this = this;

  // Shitty error log
  $el.on('validationError.queryBuilder', function (event, rule, error, value) {
    console.log(error);
    console.log(value);
  });

  $el.on('afterUpdateRuleValue.queryBuilder', function (event) {
    var currentQuery = $(event.currentTarget).queryBuilder('getRules')

    _this.autoSaveCurrentQuery(currentQuery);
    _this.refreshCheckboxVisuals($el)
  })
}

QueryBuilder.prototype.refreshCheckboxVisuals = function ($el) {
  // To update styling of check and radioboxes
  $el.find('input[type=checkbox],input[type=radio]').each(function () {
    if (this.checked) {
      $(this.parentElement).addClass('active');
      return;
    }

    $(this.parentElement).removeClass('active');
  });
}

QueryBuilder.prototype.initializeQueryBuilder = function ($el, initialQuery) {
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
        operators: ['in']
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
    rules: initialQuery,
    lang: {
      delete_rule: "×",
      delete_group: "×",
      conditions: {
        "AND": "And <i>(All)</i>",
        "OR": "Or <i>(At least one)</i>"
      }
    }
  });
}
