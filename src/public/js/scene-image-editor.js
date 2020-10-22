/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/scene-image-editor.js":
/*!********************************************!*\
  !*** ./resources/js/scene-image-editor.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

window.addEventListener('keypress', function (e) {
  if (e.code == 'KeyT') {
    if (document.getElementById('tools').classList.contains('active')) {
      document.getElementById('tools').classList.remove('active');
    } else {
      document.getElementById('tools').classList.add('active');
    }
  }
});
window.addEventListener('load', function () {
  var saveHotspot,
      savePlaceholder,
      selectTool,
      beforeSubmit,
      handlePathClicked,
      tool = null;
  var svg = document.getElementById('svg-layer');
  var x, y, path;

  selectTool = function selectTool(type) {
    return function () {
      Array.from(this.parentElement.children).forEach(function (child) {
        return child.classList.remove('active');
      });

      if (tool == type) {
        tool = null;
        document.getElementById('hotspot-form').classList.remove('active');
        document.getElementById('placeholder-form').classList.remove('active');
      } else {
        tool = type;
        this.classList.add('active');
      }
    };
  };

  beforeSubmit = function beforeSubmit(e) {
    var hotspots = Array.from(svg.querySelectorAll('[type="hotspot"]')).map(function (hotspot) {
      var x = hotspot.getAttribute('data-x');
      var y = hotspot.getAttribute('data-y');
      var w = hotspot.getAttribute('data-w');
      var h = hotspot.getAttribute('data-h');
      var id = hotspot.getAttribute('data-id') || '';
      var name = hotspot.getAttribute('data-name') || '';
      var meta = hotspot.getAttribute('data-meta') || '';
      var folders = hotspot.getAttribute('data-folders') || '';
      var medias = hotspot.getAttribute('data-medias') || '';
      return {
        position: {
          x: x,
          y: y,
          w: w,
          h: h
        },
        id: id,
        name: name,
        meta: meta,
        folders: folders,
        medias: medias
      };
    });
    var placeholders = Array.from(svg.querySelectorAll('[type="placeholder"]')).map(function (placeholder) {
      var x = placeholder.getAttribute('data-x');
      var y = placeholder.getAttribute('data-y');
      var w = placeholder.getAttribute('data-w');
      var h = placeholder.getAttribute('data-h');
      var url = placeholder.getAttribute('data-url');
      return {
        position: {
          x: x,
          y: y,
          w: w,
          h: h
        },
        url: url
      };
    });
    document.querySelector('[name="hotspots"]').value = JSON.stringify(hotspots);
    document.querySelector('[name="placeholders"]').value = JSON.stringify(placeholders);
  };

  var working = null;

  handlePathClicked = function handlePathClicked(e) {
    if (tool == 'delete') {
      this.remove();
    }

    if (tool == 'select') {
      switch (this.getAttribute('type')) {
        case 'hotspot':
          var name = this.getAttribute('data-name');
          var meta = this.getAttribute('data-meta');
          var folders = this.getAttribute('data-folders');
          var medias = this.getAttribute('data-medias');
          document.querySelector('[name="name"]').value = name;
          document.querySelector('[name="meta"]').value = meta;
          document.querySelector('[name="folders"]').value = folders;
          document.querySelector('[name="medias"]').value = medias;
          document.getElementById('placeholder-form').classList.remove('active');
          document.getElementById('hotspot-form').classList.add('active');
          working = this;
          break;

        case 'placeholder':
          var url = this.getAttribute('data-url');
          document.querySelector('[name="url"]').value = url;
          document.getElementById('hotspot-form').classList.remove('active');
          document.getElementById('placeholder-form').classList.add('active');
          working = this;
          break;
      }
    }
  };

  saveHotspot = function saveHotspot(e) {
    document.getElementById('hotspot-form').classList.remove('active');

    if (working) {
      var name = document.querySelector('[name="name"]').value;
      var meta = document.querySelector('[name="meta"]').value;
      var folders = document.querySelector('[name="folders"]').value;
      var medias = document.querySelector('[name="medias"]').value;
      working.setAttribute('data-name', name);
      working.setAttribute('data-meta', meta);
      working.setAttribute('data-folders', folders);
      working.setAttribute('data-medias', medias);
    }
  };

  savePlaceholder = function savePlaceholder(e) {
    document.getElementById('placeholder-form').classList.remove('active');
    var url = document.querySelector('[name="url"]').value;

    if (working) {
      working.setAttribute('data-url', url);
    }
  };

  document.getElementById('create-hotspot').addEventListener('click', selectTool('hotspot'));
  document.getElementById('create-placeholder').addEventListener('click', selectTool('placeholder'));
  document.getElementById('delete').addEventListener('click', selectTool('delete'));
  document.getElementById('select').addEventListener('click', selectTool('select'));
  document.getElementById('save').addEventListener('click', beforeSubmit);
  document.getElementById('save-hotspot').addEventListener('click', saveHotspot);
  document.getElementById('save-placeholder').addEventListener('click', savePlaceholder);
  document.getElementById('download').addEventListener('click', function () {
    window.scene.hotspots = window.scene.hotspots.map(function (hotspot) {
      if (hotspot.medias && hotspot.medias.constructor.name != 'Array') {
        var rows = hotspot.medias.split(/(\n|\r\n)/).filter(function (el) {
          return el && el != '\n' && el != '\r\n';
        });

        if (rows) {
          var medias = [];
          var thumbnails = rows.filter(function (row) {
            return row.includes('Thumbnail');
          });
          var inactives = rows.filter(function (row) {
            return row.includes('Inactive');
          });
          var descriptions = rows.filter(function (row) {
            return row.includes('Description');
          });
          var images = rows.filter(function (row) {
            return !row.includes('Thumbnail') && !row.includes('Inactive') && !row.includes('Description');
          });

          for (i in images) {
            var _images$i$split = images[i].split(/:(.+)/),
                _images$i$split2 = _slicedToArray(_images$i$split, 2),
                content_type = _images$i$split2[0],
                content_path = _images$i$split2[1];

            var _split = (thumbnails[i] || '').split(/:(.+)/),
                _split2 = _slicedToArray(_split, 2),
                thumbnail_path = _split2[1];

            var _split3 = (inactives[i] || '').split(/:(.+)/),
                _split4 = _slicedToArray(_split3, 2),
                inactive_path = _split4[1];

            var _split5 = (descriptions[i] || '').split(/:(.+)/),
                _split6 = _slicedToArray(_split5, 2),
                description = _split6[1];

            var media = {
              content_type: content_type,
              content_path: content_path,
              thumbnail_path: thumbnail_path,
              inactive_path: inactive_path,
              description: description
            };
            medias.push(media);
          }

          hotspot.medias = medias;
        }
      }

      return hotspot;
    });
    var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(window.scene, '', 2));
    var dlAnchorElem = document.getElementById('downloadAnchorElem');
    dlAnchorElem.setAttribute("href", dataStr);
    dlAnchorElem.setAttribute("download", window.scene.name + ".json");
    dlAnchorElem.click();
  });
  svg.setAttribute('width', svg.parentElement.clientWidth);
  svg.setAttribute('height', svg.parentElement.clientHeight);
  svg.setAttribute('viewBox', "0 0 ".concat(svg.getAttribute('width'), " ").concat(svg.getAttribute('height')));
  svg.addEventListener('mousedown', function (e) {
    var offsetX = e.offsetX,
        offsetY = e.offsetY;

    if (['hotspot', 'placeholder'].indexOf(tool) !== -1) {
      path = document.createElementNS('http://www.w3.org/2000/svg', "path");
      x = offsetX;
      y = offsetY;
      path.setAttribute('fill', tool == 'hotspot' ? 'blue' : 'red');
      path.setAttribute('type', tool);
      path.setAttribute('opacity', 0.5);
      svg.appendChild(path);
    }
  });
  svg.addEventListener('mousemove', function (e) {
    if (!path) return;
    var offsetX = e.offsetX,
        offsetY = e.offsetY;

    if (['hotspot', 'placeholder'].indexOf(tool) !== -1) {
      var width = offsetX - x;
      var height = offsetY - y;
      path.setAttribute('d', "M ".concat(x, " ").concat(y, " h ").concat(width, " v ").concat(height, " h ").concat(-width, " Z"));
    }
  });
  svg.addEventListener('mouseup', function (e) {
    if (path) {
      var offsetX = e.offsetX,
          offsetY = e.offsetY;
      var w = offsetX - x;
      var h = offsetY - y;
      if (w < 0) x = offsetX;
      if (h < 0) y = offsetY;
      path.setAttribute('data-x', Math.abs(x).toFixed(2));
      path.setAttribute('data-y', Math.abs(y).toFixed(2));
      path.setAttribute('data-w', Math.abs(w).toFixed(2));
      path.setAttribute('data-h', Math.abs(h).toFixed(2));
      path.addEventListener('click', handlePathClicked);
    }

    path = null;
  });
  Array.from(document.querySelectorAll('path')).forEach(function (path) {
    path.addEventListener('click', handlePathClicked);
  });
});

/***/ }),

/***/ 2:
/*!**************************************************!*\
  !*** multi ./resources/js/scene-image-editor.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/html/laravel/ve-editor/resources/js/scene-image-editor.js */"./resources/js/scene-image-editor.js");


/***/ })

/******/ });