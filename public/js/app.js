var App = App || {};

App.buildURL = function(template, xhprof_query, xhprof) {
  var query, url;
  if (xhprof == null) {
    xhprof = {};
  }
  url = App.config.base_url;
  if (template === null) {
    return url;
  }
  query = {
    xhprof: xhprof
  };
  if (query.xhprof) {
    query.xhprof.template = template;
  } else {
    query.xhprof = {
      template: template
    };
  }
  if (xhprof_query) {
    query.xhprof.query = xhprof_query;
  }
  return PHP.http_build_query(query);
};
