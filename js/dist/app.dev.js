"use strict";

var app = Vue.createApp({
  data: function data() {
    return {
      type: '',
      id: 0,
      credits: {},
      reviews: {},
      overview: {},
      commentarySql: {},
      favMovies: false,
      favTv: false,
      apiSession: {},
      fav: '',
      replyComment: ""
    };
  },
  computed: {
    basePath: function basePath() {
      return document.querySelector('#conf>input[name=base_path]').getAttribute('value');
    },
    httpHost: function httpHost() {
      return 'http://' + document.querySelector('#conf>input[name=http_host]').getAttribute('value');
    },
    emptyFavorite: function emptyFavorite() {
      var areEmpty = true;

      if (this.favMovies && this.favTv) {
        areEmpty = (this.favMovies.results.length > 0 || this.favTv.results.length) > 0 ? false : true;
      }

      return areEmpty;
    }
  },
  watch: {
    id: function id() {
      this.get('credits');
      this.get('reviews');
      this.get('overview');
      this.getReviewSql();
    },
    favTv: function favTv() {
      if (this.favMovies && this.favTv) {
        this.fav = this.isFav();
      }
    },
    favMovies: function favMovies() {
      if (this.favMovies && this.favTv) {
        this.fav = this.isFav();
      }
    },
    overview: function overview() {
      this.overview.title = typeof this.overview.title !== 'undefined' ? this.overview.title : this.overview.name;
    }
  },
  methods: {
    changePage: function changePage(obj) {
      var param = typeof obj.id === 'undefined' ? obj.keyword : obj.id;
      var url = this.basePath + '/' + obj.page + '/' + param;
      window.location.assign(encodeURI(url));
    },
    getPrgInfo: function getPrgInfo() {
      if (document.querySelector('#prg-info > input[name=type]') !== null) {
        this.type = document.querySelector('#prg-info > input[name=type]').getAttribute('value');
        this.id = document.querySelector('#prg-info > input[name=id]').getAttribute('value');
      }
    },
    get: function get(info) {
      var urlRequest = api.base + '/' + this.type + '/' + this.id + '/' + info + api.key;
      urlRequest = info == 'overview' ? api.base + '/' + this.type + '/' + this.id + api.key : urlRequest;
      this.getRequestApi(urlRequest, info);
    },
    getRequestApi: function getRequestApi(urlRequest, store) {
      var _this = this;

      fetch(urlRequest).then(function (response) {
        return response.json();
      }).then(function (json) {
        return _this[store] = json;
      });
    },
    getReviewSql: function getReviewSql() {
      var _this2 = this;

      var url = this.basePath + '/review/' + this.type + "/" + this.id;
      fetch(url).then(function (response) {
        return response.json();
      }).then(function (json) {
        return _this2['commentarySql'] = json;
      });
    },
    getReviewReplySql: function getReviewReplySql() {
      var _this3 = this;

      var url = +this.basePath + '/review/' + this.type + "/" + this.id;
      fetch(url).then(function (response) {
        return response.json();
      }).then(function (json) {
        return _this3['commentarySql'] = json;
      }).then(function (json) {
        _this3[store] = json;
      });
    },
    apiConnect: function apiConnect() {
      var urlRequest, token;
      return regeneratorRuntime.async(function apiConnect$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              urlRequest = api.base + '/authentication/token/new' + api.key;
              _context.next = 3;
              return regeneratorRuntime.awrap(fetch(urlRequest).then(function (response) {
                return response.json();
              }));

            case 3:
              token = _context.sent;

              if (token.success) {
                _context.next = 9;
                break;
              }

              console.log('Token request failed', token);
              return _context.abrupt("return");

            case 9:
              urlRequest = this.httpHost + this.basePath + '/token/set';
              _context.next = 12;
              return regeneratorRuntime.awrap(this.postFormData(urlRequest, {
                "token": token.request_token
              }));

            case 12:
              reponse = _context.sent;
              console.log(reponse);

            case 14:
              urlRequest = 'https://www.themoviedb.org/authenticate/' + token.request_token + '?redirect_to=' + this.httpHost + this.basePath;
              window.location.assign(urlRequest);

            case 16:
            case "end":
              return _context.stop();
          }
        }
      }, null, this);
    },
    getSession: function getSession() {
      var urlRequest, session, token;
      return regeneratorRuntime.async(function getSession$(_context2) {
        while (1) {
          switch (_context2.prev = _context2.next) {
            case 0:
              urlRequest = this.httpHost + this.basePath + '/session/get';
              _context2.next = 3;
              return regeneratorRuntime.awrap(fetch(urlRequest).then(function (reponse) {
                return reponse.json();
              }));

            case 3:
              session = _context2.sent;

              if (!(session.session.api_session.length > 0)) {
                _context2.next = 9;
                break;
              }

              this.apiSession.session_id = session.session.api_session;
              this.getFav();
              console.log('Session already started', session);
              return _context2.abrupt("return");

            case 9:
              urlRequest = this.httpHost + this.basePath + '/token/get';
              _context2.next = 12;
              return regeneratorRuntime.awrap(fetch(urlRequest).then(function (reponse) {
                return reponse.json();
              }));

            case 12:
              token = _context2.sent;

              if (!(!token.success || token.token === "" || token.token === "undefined")) {
                _context2.next = 15;
                break;
              }

              return _context2.abrupt("return", console.log('No token available', token));

            case 15:
              urlRequest = 'https://api.themoviedb.org/3/authentication/session/new' + api.key + '&request_token=' + token.token;
              _context2.next = 18;
              return regeneratorRuntime.awrap(fetch(urlRequest).then(function (reponse) {
                return reponse.json();
              }));

            case 18:
              session = _context2.sent;

              if (session.success) {
                _context2.next = 21;
                break;
              }

              return _context2.abrupt("return", console.log('No session return by api', session));

            case 21:
              urlRequest = this.httpHost + this.basePath + '/session/set';
              this.postFormData(urlRequest, {
                'session': session.session_id
              });
              urlRequest = this.httpHost + this.basePath + '/token/set';
              reponse = this.postFormData(urlRequest, {
                "token": token.token
              });
              this.getFav();

            case 26:
            case "end":
              return _context2.stop();
          }
        }
      }, null, this);
    },
    postFormData: function postFormData(url, dataSet) {
      var form, key, element, response;
      return regeneratorRuntime.async(function postFormData$(_context3) {
        while (1) {
          switch (_context3.prev = _context3.next) {
            case 0:
              form = new FormData();

              for (key in dataSet) {
                if (dataSet.hasOwnProperty.call(dataSet, key)) {
                  element = dataSet[key];
                  form.append(key, element);
                }
              }

              _context3.next = 4;
              return regeneratorRuntime.awrap(fetch(url, {
                method: 'POST',
                body: form
              }).then(function (response) {
                return response.json();
              }));

            case 4:
              response = _context3.sent;
              return _context3.abrupt("return", response);

            case 6:
            case "end":
              return _context3.stop();
          }
        }
      });
    },
    toggleFav: function toggleFav() {
      console.log('toggle');
      this.setFav(this.type, this.id, !this.isFav());
    },
    getFav: function getFav(type) {
      var urlRequest;
      return regeneratorRuntime.async(function getFav$(_context4) {
        while (1) {
          switch (_context4.prev = _context4.next) {
            case 0:
              if (!(typeof this.apiSession.session_id === 'undefined')) {
                _context4.next = 2;
                break;
              }

              return _context4.abrupt("return");

            case 2:
              urlRequest = api.base + '/account/%7Baccount_id%7D/favorite/movies' + api.key + '&session_id=' + this.apiSession.session_id + '&language=en-US&sort_by=created_at.asc&page=1';
              this.getRequestApi(urlRequest, 'favMovies');
              urlRequest = api.base + '/account/%7Baccount_id%7D/favorite/tv' + api.key + '&session_id=' + this.apiSession.session_id + '&language=en-US&sort_by=created_at.asc&page=1';
              this.getRequestApi(urlRequest, 'favTv');

            case 6:
            case "end":
              return _context4.stop();
          }
        }
      }, null, this);
    },
    setFav: function setFav(type, id, state) {
      var _this4 = this;

      if (typeof this.apiSession.session_id === 'undefined') {
        return;
      }

      var urlRequest = api.base + '/account/%7Baccount_id%7D/favorite' + api.key + '&session_id=' + this.apiSession.session_id;
      var fav = {
        "media_type": type,
        "media_id": id,
        "favorite": state
      };
      fetch(urlRequest, {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(fav),
        method: 'POST'
      }).then(function (reponse) {
        return reponse.json();
      }).then(function (json) {
        console.log(json);
        _this4.favMovies = false;
        _this4.favTv = false;

        _this4.getFav();
      });
    },
    isFav: function isFav() {
      var _this5 = this;

      var isFav = false;

      if (this.type == 'movie') {
        this.favMovies.results.forEach(function (fav) {
          if (fav.id == _this5.id) {
            isFav = true;
          }
        });
      } else {
        this.favTv.results.forEach(function (fav) {
          if (fav.id == _this5.id) {
            isFav = true;
          }
        });
      }

      return isFav;
    },
    addReply: function addReply(event) {
      this.replyComment = event.id_comment;
      console.log(this.replyComment);
    },
    addComment: function addComment() {
      var urlRequest, commentary;
      return regeneratorRuntime.async(function addComment$(_context5) {
        while (1) {
          switch (_context5.prev = _context5.next) {
            case 0:
              console.log(this.replyComment);
              urlRequest = this.basePath + '/review/new/' + this.type + "/" + this.id;
              commentary = document.querySelector('#writeComment').value;
              _context5.next = 5;
              return regeneratorRuntime.awrap(this.postFormData(urlRequest, {
                "replyComment": this.replyComment,
                "commentary": commentary
              }));

            case 5:
              reponse = _context5.sent;
              console.log(reponse);
              this.getReviewSql();

            case 8:
            case "end":
              return _context5.stop();
          }
        }
      }, null, this);
    }
  },
  mounted: function mounted() {
    this.getPrgInfo();
    this.getSession();
  }
});
app.component('api-connect', {
  template: "<button class='btn btn-primary' @click=\"$emit('apiConnect')\">Connect Api</button>"
});
app.component('search-modul', {
  data: function data() {
    return {
      query: '',
      dataList: {},
      results: {},
      picLowQual: api.picLowQual
    };
  },
  props: ['keywords'],
  computed: {
    showResults: function showResults() {
      return this.keywords.length > 0 ? true : false;
    }
  },
  watch: {
    query: function query() {
      this.getDataList();
    }
  },
  methods: {
    getDataList: function getDataList() {
      var urlRequest = api.base + '/' + 'search/multi' + api.key + '&query=' + this.query;
      this.getRequestApi(urlRequest, 'dataList');
    },
    getResults: function getResults() {
      var urlRequest = api.base + '/' + 'search/multi' + api.key + '&query=' + this.keywords;
      this.getRequestApi(urlRequest, 'results');
    },
    getRequestApi: function getRequestApi(urlRequest, store) {
      var _this6 = this;

      fetch(urlRequest).then(function (response) {
        return response.json();
      }).then(function (json) {
        return _this6[store] = json;
      });
    },
    title: function title(data) {
      switch (data.media_type) {
        case 'movie':
          return data.title;
          break;

        case 'tv':
          return data.name;
          break;

        default:
          break;
      }
    },
    showResult: function showResult(result) {
      return (result.media_type === 'tv' || result.media_type === 'movie') && result.poster_path !== null ? true : false;
    }
  },
  mounted: function mounted() {
    if (this.showResults) {
      this.getResults();
    }
  },
  template: "<div>\n        <teleport to=\"#searchBarPlaceHolder\">\n            <form @submit.prevent=\"$emit('changePage',{ page :'search', keyword:query})\" class=\"d-flex\">   \n                <input class=\"form-control me-2\"  placeholder=\"Search\" type=\"search\" list=\"keywords\" v-model=\"query\" />\n                <datalist id=\"keywords\">\n                    <option v-for=\"result in dataList.results\" :value=\"title(result)\" />\n\n                </datalist>\n                <button class=\"btn btn-outline-danger\" type=\"submit\">Search</button>\n            </form>\n        </teleport>\n        <div v-if=\"showResults\" >\n            <h2>Results for : {{keywords}}</h2>\n            <div class=\"result\" @click=\"$emit('changePage',{page:result.media_type , id:result.id})\" v-for=\"result in results.results\">\n                <div v-if=\"showResult(result)\">\n                    <img class=\"result__img\" v-bind:src=\"picLowQual + result.poster_path\">\n                    <div class=\"result__content\">\n                        <h3>{{title(result)}} <span class=\"result__type\">type: {{result.media_type }}</span></h3> \n                        <p>{{result.overview}}</p>\n                    </div>\n                    <div class=\"result__clear\"></div>\n                </div>\n            </div>\n        </div>\n    </div>\n    "
});
app.component('prg-overview', {
  data: function data() {
    return {
      picHighQual: api.picHighQual
    };
  },
  computed: {
    img_path: function img_path() {
      return this.info.backdrop_path != null ? this.info.backdrop_path : this.info.poster_path;
    },
    heart: function heart() {
      return {
        'heart': this.fav !== '' ? true : false,
        'heart--red': this.fav === true ? true : false,
        'heart--black': this.fav === false ? true : false
      };
    }
  },
  props: ["info", "fav"],
  template: "<div>\n            <div class=\"prg_info\">\n                    <img class=\"prg_info__img\" v-bind:src=\"picHighQual + img_path\" >\n                    <div class=\"prg_info__content\">\n                        <h3 class=\"prg_info__title\">{{ info.title }}</h3>  \n                        <p class=\"prg_info__overview\">{{ info.overview }}</p>\n                        </div>\n                    <div class=\"prg_info__favorite\">\n                        <div :class=\"heart\" @click=\"$emit('toggleFav')\"></div>\n                    </div>\n                    <p class=\"prg_info__note\"> {{ info.vote_average }} / 10</p>\n            </div>   \n        </div>"
});
app.component('prg-review', {
  props: ['review'],
  computed: {
    imgAvatar: function imgAvatar() {
      var nonePicPath = 'https://static.wixstatic.com/media/109580_c3da31ed06484c7e8e225c46beecd507~mv2.png/v1/fill/w_220,h_220,al_c,q_85,usm_0.66_1.00_0.01/avatar%20neutre.webp';

      if (typeof this.review.author_details == 'undefined') {
        return this.review.image;
      }

      var picPath = this.review.author_details.avatar_path === null ? nonePicPath : this.review.author_details.avatar_path.substring(1);
      return picPath.match(/^http/gmi) ? picPath : api.picLowQual + picPath;
    },
    rating: function rating() {
      if (typeof this.review.author_details != 'undefined') return this.review.author_details.rating + '/10';else {
        return "";
      }
    },
    isApi: function isApi() {
      return {
        'summary_review_api': typeof this.review.author_details == 'undefined' ? false : true
      };
    },
    isReply: function isReply() {
      return {
        'review_reply': typeof this.review.author_details == 'undefined' && typeof this.review.id_commentaire == 'undefined' ? true : false
      };
    },
    isReply_input: function isReply_input() {
      if (typeof this.review.author_details == 'undefined' && typeof this.review.id_commentaire !== 'undefined') {
        return true;
      } else {
        return false;
      }
    },
    dateFormat: function dateFormat() {
      if (typeof this.review.author_details == 'undefined') {
        return this.review.created_at.substring(0, this.review.created_at.length - 3);
      } else {
        return this.review.created_at.replace('T', ' ').substring(0, this.review.created_at.length - 8);
      }
    }
  },
  template: "<details class=\"prg-review\" :class='isReply'>\n            <summary  class=\"prg-review__summary\" :class='isApi' :class='isReply'>{{review.author}} \n                <span class=\"prg-review__note\">({{rating}})</span>\n                <button type=\"button\" v-if='isReply_input' @click='$emit(\"addReply\",{id_comment : review.id_commentaire})' \n                    :value=\"review.id_commentaire\">Repondre\n                </button>\n                </summary>\n                <img class=\"prg-review__avatar\" v-bind:src=\"imgAvatar\">\n                <p class=\"prg-review__content\" > {{review.content}}</p>\n                <span class=\"date_format\">{{dateFormat}}</span>                \n                \n        <div class=\"prg-review__clear\"></div>\n    </details>"
});
app.component('prg-casting', {
  props: ['actor'],
  template: "<div class=\"prg-actor\">\n        <img v-if=\"actor.profile_path\" class=\"prg-actor__img\" v-bind:src=\"'https://image.tmdb.org/t/p/w500//'+ actor.profile_path\">\n        <img v-else class=\"prg-actor__img\" height=\"400\" width=\"200\" src=\"https://pukt.pl/wp-content/uploads/2019/12/YPS__human_avatar_portrait_photography_picture_photo-512-300x300.png\">\n        <p class=\"prg-actor__name\">{{actor.name}} as {{actor.character}}</p>\n    </div>"
});
app.component('carrousel-custom', {
  data: function data() {
    return {
      results: {}
    };
  },
  props: ['size', 'request', 'filter'],
  methods: {
    getData: function getData() {
      var _this7 = this;

      var url = api.base + this.request + api.key + this.filter;
      fetch(url).then(function (response) {
        return response.json();
      }).then(function (json) {
        return _this7.results = json.results;
      });
    }
  },
  mounted: function mounted() {
    this.getData();
  },
  template: "<div class=\"carrousel\" >\n            <carrousel-item @change-page=\"$emit('changePage',$event)\" class=\"carrousel__item\" v-for=\"result in results\"  :result=\"result\" :size=\"size\" :key=\"result.id\"></carrousel-item>\n        </div>"
});
app.component('carrousel-item', {
  data: function data() {
    return {
      show: false,
      picLowQual: api.picLowQual
    };
  },
  props: ['result', 'size'],
  computed: {
    classSizeModifObj: function classSizeModifObj() {
      return {
        'car_item__img--big': this.size === 'big' ? true : false,
        'car_item__img--medium': this.size === 'medium' ? true : false,
        'car_item__img--small': this.size === 'small' ? true : false
      };
    }
  },
  methods: {
    toggleVisibility: function toggleVisibility() {
      this.show = this.show === true ? false : true;
    }
  },
  template: "<div v-if=\"result.poster_path !== null\" >\n            <img @click=\"toggleVisibility\" class=\"car_item__img\" :class=\"classSizeModifObj\" v-bind:src=\"picLowQual + result.poster_path\" >\n            <modal-custom @closeModal=\"toggleVisibility\" @change-page=\"$emit('changePage',$event)\" v-if=\"show\" :result=\"result\"></modal-custom>\n        </div>"
});
app.component('modal-custom', {
  data: function data() {
    return {
      picHighQual: api.picHighQual,
      type: '',
      id: this.result.id
    };
  },
  props: ['result'],
  computed: {
    img_path: function img_path() {
      return this.result.backdrop_path != null ? this.result.backdrop_path : this.result.poster_path;
    }
  },
  methods: {
    titleHandeling: function titleHandeling() {
      this.type = typeof this.result.title !== 'undefined' ? 'movie' : 'tv';
      this.result.title = typeof this.result.title !== 'undefined' ? this.result.title : this.result.name;
    }
  },
  mounted: function mounted() {
    this.titleHandeling();
  },
  template: "<div class=\"modal\">\n            <button @click=\"$emit('closeModal')\" class=\"modal__close_btn\">X</button>\n            <img class=\"modal__photo\" v-bind:src=\"picHighQual + img_path\" >\n            <div class=\"modal__content\">\n                <h3>{{ result.title }}</h3>\n                <p>Overview : {{ result.overview }}</p>\n                <p>Vote : {{ result.vote_average }}</p>\n                <button @click=\"$emit('changePage', { page : type, id : id })\">More information</button>\n            </div>\n        </div>"
});
app.component('favorite-list', {
  data: function data() {
    return {
      picLowQual: api.picLowQual
    };
  },
  props: ['favList'],
  emits: ['changePage'],
  methods: {
    type: function type(result) {
      return typeof result.title !== 'undefined' ? 'movie' : 'tv';
    },
    title: function title(result) {
      return typeof result.title !== 'undefined' ? result.title : result.name;
    }
  },
  template: "\n    <div class=\"result\" @click=\"$emit('changePage',{page: type(result) , id:result.id})\" v-for=\"result in favList.results\">\n        <img class=\"result__img\" v-bind:src=\"picLowQual + result.poster_path\">\n        <div class=\"result__content\">\n            <h3>{{title(result)}} <span class=\"result__type\">type: {{ type(result) }}</span></h3> \n            <p>{{result.overview}}</p>\n        </div>\n        <div class=\"result__clear\"></div>\n    </div>\n    "
});
var vm = app.mount('#app');