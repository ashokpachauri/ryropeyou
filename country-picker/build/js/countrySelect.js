// wrap in UMD - see https://github.com/umdjs/umd/blob/master/jqueryPlugin.js
(function(factory) {
	if (typeof define === "function" && define.amd) {
		define([ "jquery" ], function($) {
			factory($, window, document);
		});
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory(require("jquery"), window, document);
	} else {
		factory(jQuery, window, document);
	}
})(function($, window, document, undefined) {
	"use strict";
	var pluginName = "countrySelect", id = 1, // give each instance its own ID for namespaced event handling
	defaults = {
		// Default country
		defaultCountry: "in",
		// Position the selected flag inside or outside of the input
		defaultStyling: "inside",
		// don't display these countries
		excludeCountries: [],
		// Display only these countries
		onlyCountries: [],
		// The countries at the top of the list. Defaults to United States and United Kingdom
		preferredCountries: [ "us", "gb" ],
		// Set the dropdown's width to be the same as the input. This is automatically enabled for small screens.
		responsiveDropdown: ($(window).width() < 768 ? true : false),
	}, keys = {
		UP: 38,
		DOWN: 40,
		ENTER: 13,
		ESC: 27,
		BACKSPACE: 8,
		PLUS: 43,
		SPACE: 32,
		A: 65,
		Z: 90
	}, windowLoaded = false;
	// keep track of if the window.load event has fired as impossible to check after the fact
	$(window).on('load', function() {
		windowLoaded = true;
	});
	function Plugin(element, options) {
		this.element = element;
		this.options = $.extend({}, defaults, options);
		this._defaults = defaults;
		// event namespace
		this.ns = "." + pluginName + id++;
		this._name = pluginName;
		this.init();
	}
	Plugin.prototype = {
		init: function() {
			// Process all the data: onlyCountries, excludeCountries, preferredCountries, defaultCountry etc
			this._processCountryData();
			// Generate the markup
			this._generateMarkup();
			// Set the initial state of the input value and the selected flag
			this._setInitialState();
			// Start all of the event listeners: input keyup, selectedFlag click
			this._initListeners();
			// Return this when the auto country is resolved.
			this.autoCountryDeferred = new $.Deferred();
			// Get auto country.
			this._initAutoCountry();
			// Keep track as the user types
		        this.typedLetters = "";

			return this.autoCountryDeferred;
		},
		/********************
		 *  PRIVATE METHODS
		 ********************/
		// prepare all of the country data, including onlyCountries, excludeCountries, preferredCountries and
		// defaultCountry options
		_processCountryData: function() {
			// set the instances country data objects
			this._setInstanceCountryData();
			// set the preferredCountries property
			this._setPreferredCountries();
		},
		// process onlyCountries array if present
		_setInstanceCountryData: function() {
			var that = this;
			if (this.options.onlyCountries.length) {
				var newCountries = [];
				$.each(this.options.onlyCountries, function(i, countryCode) {
					var countryData = that._getCountryData(countryCode, true);
					if (countryData) {
						newCountries.push(countryData);
					}
				});
				this.countries = newCountries;
			} else if (this.options.excludeCountries.length) {
                var lowerCaseExcludeCountries = this.options.excludeCountries.map(function(country) {
                    return country.toLowerCase();
                });
                this.countries = allCountries.filter(function(country) {
                    return lowerCaseExcludeCountries.indexOf(country.iso2) === -1;
                });
            } else {
				this.countries = allCountries;
			}
		},
		// Process preferred countries - iterate through the preferences,
		// fetching the country data for each one
		_setPreferredCountries: function() {
			var that = this;
			this.preferredCountries = [];
			$.each(this.options.preferredCountries, function(i, countryCode) {
				var countryData = that._getCountryData(countryCode, false);
				if (countryData) {
					that.preferredCountries.push(countryData);
				}
			});
		},
		// generate all of the markup for the plugin: the selected flag overlay, and the dropdown
		_generateMarkup: function() {
			// Country input
			this.countryInput = $(this.element);
			// containers (mostly for positioning)
			var mainClass = "country-select";
			if (this.options.defaultStyling) {
				mainClass += " " + this.options.defaultStyling;
			}
			this.countryInput.wrap($("<div>", {
				"class": mainClass
			}));
			var flagsContainer = $("<div>", {
				"class": "flag-dropdown"
			}).insertAfter(this.countryInput);
			// currently selected flag (displayed to left of input)
			var selectedFlag = $("<div>", {
				"class": "selected-flag"
			}).appendTo(flagsContainer);
			this.selectedFlagInner = $("<div>", {
				"class": "flag"
			}).appendTo(selectedFlag);
			// CSS triangle
			$("<div>", {
				"class": "arrow"
			}).appendTo(selectedFlag);
			// country list contains: preferred countries, then divider, then all countries
			this.countryList = $("<ul>", {
				"class": "country-list v-hide"
			}).appendTo(flagsContainer);
			if (this.preferredCountries.length) {
				this._appendListItems(this.preferredCountries, "preferred");
				$("<li>", {
					"class": "divider"
				}).appendTo(this.countryList);
			}
			this._appendListItems(this.countries, "");
			// Add the hidden input for the country code
			this.countryCodeInput = $("#"+this.countryInput.attr("id")+"_code");
			if (!this.countryCodeInput) {
				this.countryCodeInput = $('<input type="hidden" id="'+this.countryInput.attr("id")+'_code" name="'+this.countryInput.attr("name")+'_code" value="" />');
				this.countryCodeInput.insertAfter(this.countryInput);
			}
			// now we can grab the dropdown height, and hide it properly
			this.dropdownHeight = this.countryList.outerHeight();
			// set the dropdown width according to the input if responsiveDropdown option is present or if it's a small screen
			if (this.options.responsiveDropdown) {
				$(window).resize(function() {
					$('.country-select').each(function() {
						var dropdownWidth = this.offsetWidth;
						$(this).find('.country-list').css("width", dropdownWidth + "px");
					});
				}).resize();
			}
			this.countryList.removeClass("v-hide").addClass("hide");
			// this is useful in lots of places
			this.countryListItems = this.countryList.children(".country");
		},
		// add a country <li> to the countryList <ul> container
		_appendListItems: function(countries, className) {
			// Generate DOM elements as a large temp string, so that there is only
			// one DOM insert event
			var tmp = "";
			// for each country
			$.each(countries, function(i, c) {
				// open the list item
				tmp += '<li class="country ' + className + '" data-country-code="' + c.iso2 + '">';
				// add the flag
				tmp += '<div class="flag ' + c.iso2 + '"></div>';
				// and the country name
				tmp += '<span class="country-name">' + c.name + '</span>';
				// close the list item
				tmp += '</li>';
			});
			this.countryList.append(tmp);
		},
		// set the initial state of the input value and the selected flag
		_setInitialState: function() {
			var flagIsSet = false;
			// If the input is pre-populated, then just update the selected flag
			if (this.countryInput.val()) {
				flagIsSet = this._updateFlagFromInputVal();
			}
			// If the country code input is pre-populated, update the name and the selected flag
			var selectedCode = this.countryCodeInput.val();
			if (selectedCode) {
				this.selectCountry(selectedCode);
			}
			if (!flagIsSet) {
				// flag is not set, so set to the default country
				var defaultCountry;
				// check the defaultCountry option, else fall back to the first in the list
				if (this.options.defaultCountry) {
					defaultCountry = this._getCountryData(this.options.defaultCountry, false);
					// Did we not find the requested default country?
					if (!defaultCountry) {
						defaultCountry = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0];
					}
				} else {
					defaultCountry = this.preferredCountries.length ? this.preferredCountries[0] : this.countries[0];
				}
				this.defaultCountry = defaultCountry.iso2;
			}
		},
		// initialise the main event listeners: input keyup, and click selected flag
		_initListeners: function() {
			var that = this;
			// Update flag on keyup.
			// Use keyup instead of keypress because we want to update on backspace
			// and instead of keydown because the value hasn't updated when that
			// event is fired.
			// NOTE: better to have this one listener all the time instead of
			// starting it on focus and stopping it on blur, because then you've
			// got two listeners (focus and blur)
			this.countryInput.on("keyup" + this.ns, function() {
				that._updateFlagFromInputVal();
			});
			// toggle country dropdown on click
			var selectedFlag = this.selectedFlagInner.parent();
			selectedFlag.on("click" + this.ns, function(e) {
				// only intercept this event if we're opening the dropdown
				// else let it bubble up to the top ("click-off-to-close" listener)
				// we cannot just stopPropagation as it may be needed to close another instance
				if (that.countryList.hasClass("hide") && !that.countryInput.prop("disabled")) {
					that._showDropdown();
				}
			});
			// Despite above note, added blur to ensure partially spelled country
			// with correctly chosen flag is spelled out on blur. Also, correctly
			// selects flag when field is autofilled
			this.countryInput.on("blur" + this.ns, function() {
				if (that.countryInput.val() != that.getSelectedCountryData().name) {
					that.setCountry(that.countryInput.val());
				}
				that.countryInput.val(that.getSelectedCountryData().name);
			});
		},
		_initAutoCountry: function() {
			if (this.options.initialCountry === "auto") {
				this._loadAutoCountry();
			} else {
				if (this.defaultCountry) {
					this.selectCountry(this.defaultCountry);
				}
				this.autoCountryDeferred.resolve();
			}
		},
		// perform the geo ip lookup
		_loadAutoCountry: function() {
			var that = this;

			// 3 options:
			// 1) already loaded (we're done)
			// 2) not already started loading (start)
			// 3) already started loading (do nothing - just wait for loading callback to fire)
			if ($.fn[pluginName].autoCountry) {
				this.handleAutoCountry();
			} else if (!$.fn[pluginName].startedLoadingAutoCountry) {
				// don't do this twice!
				$.fn[pluginName].startedLoadingAutoCountry = true;

				if (typeof this.options.geoIpLookup === 'function') {
					this.options.geoIpLookup(function(countryCode) {
						$.fn[pluginName].autoCountry = countryCode.toLowerCase();
						// tell all instances the auto country is ready
						// TODO: this should just be the current instances
						// UPDATE: use setTimeout in case their geoIpLookup function calls this callback straight away (e.g. if they have already done the geo ip lookup somewhere else). Using setTimeout means that the current thread of execution will finish before executing this, which allows the plugin to finish initialising.
						setTimeout(function() {
							$(".country-select input").countrySelect("handleAutoCountry");
						});
					});
				}
			}
		},
		// Focus input and put the cursor at the end
		_focus: function() {
			this.countryInput.focus();
			var input = this.countryInput[0];
			// works for Chrome, FF, Safari, IE9+
			if (input.setSelectionRange) {
				var len = this.countryInput.val().length;
				input.setSelectionRange(len, len);
			}
		},
		// Show the dropdown
		_showDropdown: function() {
			this._setDropdownPosition();
			// update highlighting and scroll to active list item
			var activeListItem = this.countryList.children(".active");
			this._highlightListItem(activeListItem);
			// show it
			this.countryList.removeClass("hide");
			this._scrollTo(activeListItem);
			// bind all the dropdown-related listeners: mouseover, click, click-off, keydown
			this._bindDropdownListeners();
			// update the arrow
			this.selectedFlagInner.parent().children(".arrow").addClass("up");
		},
		// decide where to position dropdown (depends on position within viewport, and scroll)
		_setDropdownPosition: function() {
			var inputTop = this.countryInput.offset().top, windowTop = $(window).scrollTop(),
			dropdownFitsBelow = inputTop + this.countryInput.outerHeight() + this.dropdownHeight < windowTop + $(window).height(), dropdownFitsAbove = inputTop - this.dropdownHeight > windowTop;
			// dropdownHeight - 1 for border
			var cssTop = !dropdownFitsBelow && dropdownFitsAbove ? "-" + (this.dropdownHeight - 1) + "px" : "";
			this.countryList.css("top", cssTop);
		},
		// we only bind dropdown listeners when the dropdown is open
		_bindDropdownListeners: function() {
			var that = this;
			// when mouse over a list item, just highlight that one
			// we add the class "highlight", so if they hit "enter" we know which one to select
			this.countryList.on("mouseover" + this.ns, ".country", function(e) {
				that._highlightListItem($(this));
			});
			// listen for country selection
			this.countryList.on("click" + this.ns, ".country", function(e) {
				that._selectListItem($(this));
			});
			// click off to close
			// (except when this initial opening click is bubbling up)
			// we cannot just stopPropagation as it may be needed to close another instance
			var isOpening = true;
			$("html").on("click" + this.ns, function(e) {
				e.preventDefault();
				if (!isOpening) {
					that._closeDropdown();
				}
				isOpening = false;
			});
			// Listen for up/down scrolling, enter to select, or letters to jump to country name.
			// Use keydown as keypress doesn't fire for non-char keys and we want to catch if they
			// just hit down and hold it to scroll down (no keyup event).
			// Listen on the document because that's where key events are triggered if no input has focus
			$(document).on("keydown" + this.ns, function(e) {
				// prevent down key from scrolling the whole page,
				// and enter key from submitting a form etc
				e.preventDefault();
				if (e.which == keys.UP || e.which == keys.DOWN) {
					// up and down to navigate
					that._handleUpDownKey(e.which);
				} else if (e.which == keys.ENTER) {
					// enter to select
					that._handleEnterKey();
				} else if (e.which == keys.ESC) {
					// esc to close
					that._closeDropdown();
				} else if (e.which >= keys.A && e.which <= keys.Z || e.which === keys.SPACE) {
					that.typedLetters += String.fromCharCode(e.which);
					that._filterCountries(that.typedLetters);
				} else if (e.which === keys.BACKSPACE) {
					that.typedLetters = that.typedLetters.slice(0, -1);
					that._filterCountries(that.typedLetters);
				}
			});
		},
		// Highlight the next/prev item in the list (and ensure it is visible)
		_handleUpDownKey: function(key) {
			var current = this.countryList.children(".highlight").first();
			var next = key == keys.UP ? current.prev() : current.next();
			if (next.length) {
				// skip the divider
				if (next.hasClass("divider")) {
					next = key == keys.UP ? next.prev() : next.next();
				}
				this._highlightListItem(next);
				this._scrollTo(next);
			}
		},
		// select the currently highlighted item
		_handleEnterKey: function() {
			var currentCountry = this.countryList.children(".highlight").first();
			if (currentCountry.length) {
				this._selectListItem(currentCountry);
			}
		},
		_filterCountries: function(letters) {
			var countries = this.countryListItems.filter(function() {
				return $(this).text().toUpperCase().indexOf(letters) === 0 && !$(this).hasClass("preferred");
			});
			if (countries.length) {
				// if one is already highlighted, then we want the next one
				var highlightedCountry = countries.filter(".highlight").first(), listItem;
				if (highlightedCountry && highlightedCountry.next() && highlightedCountry.next().text().toUpperCase().indexOf(letters) === 0) {
					listItem = highlightedCountry.next();
				} else {
					listItem = countries.first();
				}
				// update highlighting and scroll
				this._highlightListItem(listItem);
				this._scrollTo(listItem);
			}
		},
		// Update the selected flag using the input's current value
		_updateFlagFromInputVal: function() {
			var that = this;
			// try and extract valid country from input
			var value = this.countryInput.val().replace(/(?=[() ])/g, '\\');
			if (value) {
				var countryCodes = [];
				var matcher = new RegExp("^"+value, "i");
				for (var i = 0; i < this.countries.length; i++) {
					if (this.countries[i].name.match(matcher)) {
						countryCodes.push(this.countries[i].iso2);
					}
				}
				// Check if one of the matching countries is already selected
				var alreadySelected = false;
				$.each(countryCodes, function(i, c) {
					if (that.selectedFlagInner.hasClass(c)) {
						alreadySelected = true;
					}
				});
				if (!alreadySelected) {
					this._selectFlag(countryCodes[0]);
					this.countryCodeInput.val(countryCodes[0]).trigger("change");
				}
				// Matching country found
				return true;
			}
			// No match found
			return false;
		},
		// remove highlighting from other list items and highlight the given item
		_highlightListItem: function(listItem) {
			this.countryListItems.removeClass("highlight");
			listItem.addClass("highlight");
		},
		// find the country data for the given country code
		// the ignoreOnlyCountriesOption is only used during init() while parsing the onlyCountries array
		_getCountryData: function(countryCode, ignoreOnlyCountriesOption) {
			var countryList = ignoreOnlyCountriesOption ? allCountries : this.countries;
			for (var i = 0; i < countryList.length; i++) {
				if (countryList[i].iso2 == countryCode) {
					return countryList[i];
				}
			}
			return null;
		},
		// update the selected flag and the active list item
		_selectFlag: function(countryCode) {
			if (! countryCode) {
				return false;
			}
			this.selectedFlagInner.attr("class", "flag " + countryCode);
			// update the title attribute
			var countryData = this._getCountryData(countryCode);
			this.selectedFlagInner.parent().attr("title", countryData.name);
			// update the active list item
			var listItem = this.countryListItems.children(".flag." + countryCode).first().parent();
			this.countryListItems.removeClass("active");
			listItem.addClass("active");
		},
		// called when the user selects a list item from the dropdown
		_selectListItem: function(listItem) {
			// update selected flag and active list item
			var countryCode = listItem.attr("data-country-code");
			this._selectFlag(countryCode);
			this._closeDropdown();
			// update input value
			this._updateName(countryCode);
			this.countryInput.trigger("change");
			this.countryCodeInput.trigger("change");
			// focus the input
			this._focus();
		},
		// close the dropdown and unbind any listeners
		_closeDropdown: function() {
			this.countryList.addClass("hide");
			// update the arrow
			this.selectedFlagInner.parent().children(".arrow").removeClass("up");
			// unbind event listeners
			$(document).off("keydown" + this.ns);
			$("html").off("click" + this.ns);
			// unbind both hover and click listeners
			this.countryList.off(this.ns);
			this.typedLetters = "";
		},
		// check if an element is visible within its container, else scroll until it is
		_scrollTo: function(element) {
			if (!element || !element.offset()) {
				return;
			}
			var container = this.countryList, containerHeight = container.height(), containerTop = container.offset().top, containerBottom = containerTop + containerHeight, elementHeight = element.outerHeight(), elementTop = element.offset().top, elementBottom = elementTop + elementHeight, newScrollTop = elementTop - containerTop + container.scrollTop();
			if (elementTop < containerTop) {
				// scroll up
				container.scrollTop(newScrollTop);
			} else if (elementBottom > containerBottom) {
				// scroll down
				var heightDifference = containerHeight - elementHeight;
				container.scrollTop(newScrollTop - heightDifference);
			}
		},
		// Replace any existing country name with the new one
		_updateName: function(countryCode) {
			this.countryCodeInput.val(countryCode).trigger("change");
			this.countryInput.val(this._getCountryData(countryCode).name);
		},
		/********************
		 *  PUBLIC METHODS
		 ********************/
		// this is called when the geoip call returns
		handleAutoCountry: function() {
			if (this.options.initialCountry === "auto") {
				// we must set this even if there is an initial val in the input: in case the initial val is invalid and they delete it - they should see their auto country
				this.defaultCountry = $.fn[pluginName].autoCountry;
				// if there's no initial value in the input, then update the flag
				if (!this.countryInput.val()) {
					this.selectCountry(this.defaultCountry);
				}
				this.autoCountryDeferred.resolve();
			}
		},
		// get the country data for the currently selected flag
		getSelectedCountryData: function() {
			// rely on the fact that we only set 2 classes on the selected flag element:
			// the first is "flag" and the second is the 2-char country code
			var countryCode = this.selectedFlagInner.attr("class").split(" ")[1];
			return this._getCountryData(countryCode);
		},
		// update the selected flag
		selectCountry: function(countryCode) {
			countryCode = countryCode.toLowerCase();
			// check if already selected
			if (!this.selectedFlagInner.hasClass(countryCode)) {
				this._selectFlag(countryCode);
				this._updateName(countryCode);
			}
		},
		// set the input value and update the flag
		setCountry: function(country) {
			this.countryInput.val(country);
			this._updateFlagFromInputVal();
		},
		// remove plugin
		destroy: function() {
			// stop listeners
			this.countryInput.off(this.ns);
			this.selectedFlagInner.parent().off(this.ns);
			// remove markup
			var container = this.countryInput.parent();
			container.before(this.countryInput).remove();
		}
	};
	// adapted to allow public functions
	// using https://github.com/jquery-boilerplate/jquery-boilerplate/wiki/Extending-jQuery-Boilerplate
	$.fn[pluginName] = function(options) {
		var args = arguments;
		// Is the first parameter an object (options), or was omitted,
		// instantiate a new instance of the plugin.
		if (options === undefined || typeof options === "object") {
			return this.each(function() {
				if (!$.data(this, "plugin_" + pluginName)) {
					$.data(this, "plugin_" + pluginName, new Plugin(this, options));
				}
			});
		} else if (typeof options === "string" && options[0] !== "_" && options !== "init") {
			// If the first parameter is a string and it doesn't start
			// with an underscore or "contains" the `init`-function,
			// treat this as a call to a public method.
			// Cache the method call to make it possible to return a value
			var returns;
			this.each(function() {
				var instance = $.data(this, "plugin_" + pluginName);
				// Tests that there's already a plugin-instance
				// and checks that the requested public method exists
				if (instance instanceof Plugin && typeof instance[options] === "function") {
					// Call the method of our plugin instance,
					// and pass it the supplied arguments.
					returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
				}
				// Allow instances to be destroyed via the 'destroy' method
				if (options === "destroy") {
					$.data(this, "plugin_" + pluginName, null);
				}
			});
			// If the earlier cached method gives a value back return the value,
			// otherwise return this to preserve chainability.
			return returns !== undefined ? returns : this;
		}
	};
	/********************
   *  STATIC METHODS
   ********************/
	// get the country data object
	$.fn[pluginName].getCountryData = function() {
		return allCountries;
	};
	// set the country data object
	$.fn[pluginName].setCountryData = function(obj) {
		allCountries = obj;
	};
	// Tell JSHint to ignore this warning: "character may get silently deleted by one or more browsers"
	// jshint -W100
	// Array of country objects for the flag dropdown.
	// Each contains a name and country code (ISO 3166-1 alpha-2).
	//
	// Note: using single char property names to keep filesize down
	// n = name
	// i = iso2 (2-char country code)
	var allCountries = $.each([{"id":"3","n":"Algeria (+213)","i":"dz","pc":"+213"},{"id":"4","n":"American Samoa (+1684)","i":"as","pc":"+1684"},{"id":"5","n":"Andorra (+376)","i":"ad","pc":"+376"},{"id":"6","n":"Angola (+244)","i":"ao","pc":"+244"},{"id":"7","n":"Anguilla (+1264)","i":"ai","pc":"+1264"},{"id":"8","n":"Antarctica (+0)","i":"aq","pc":"+0"},{"id":"9","n":"Antigua And Barbuda (+1268)","i":"ag","pc":"+1268"},{"id":"10","n":"Argentina (+54)","i":"ar","pc":"+54"},{"id":"11","n":"Armenia (+374)","i":"am","pc":"+374"},{"id":"12","n":"Aruba (+297)","i":"aw","pc":"+297"},{"id":"13","n":"Australia (+61)","i":"au","pc":"+61"},{"id":"14","n":"Austria (+43)","i":"at","pc":"+43"},{"id":"15","n":"Azerbaijan (+994)","i":"az","pc":"+994"},{"id":"16","n":"Bahamas The (+1242)","i":"bs","pc":"+1242"},{"id":"17","n":"Bahrain (+973)","i":"bh","pc":"+973"},{"id":"18","n":"Bangladesh (+880)","i":"bd","pc":"+880"},{"id":"19","n":"Barbados (+1246)","i":"bb","pc":"+1246"},{"id":"20","n":"Belarus (+375)","i":"by","pc":"+375"},{"id":"21","n":"Belgium (+32)","i":"be","pc":"+32"},{"id":"22","n":"Belize (+501)","i":"bz","pc":"+501"},{"id":"23","n":"Benin (+229)","i":"bj","pc":"+229"},{"id":"24","n":"Bermuda (+1441)","i":"bm","pc":"+1441"},{"id":"25","n":"Bhutan (+975)","i":"bt","pc":"+975"},{"id":"26","n":"Bolivia (+591)","i":"bo","pc":"+591"},{"id":"27","n":"Bosnia and Herzegovina (+387)","i":"ba","pc":"+387"},{"id":"28","n":"Botswana (+267)","i":"bw","pc":"+267"},{"id":"29","n":"Bouvet Island (+0)","i":"bv","pc":"+0"},{"id":"30","n":"Brazil (+55)","i":"br","pc":"+55"},{"id":"31","n":"British Indian Ocean Territory (+246)","i":"io","pc":"+246"},{"id":"32","n":"Brunei (+673)","i":"bn","pc":"+673"},{"id":"33","n":"Bulgaria (+359)","i":"bg","pc":"+359"},{"id":"34","n":"Burkina Faso (+226)","i":"bf","pc":"+226"},{"id":"35","n":"Burundi (+257)","i":"bi","pc":"+257"},{"id":"36","n":"Cambodia (+855)","i":"kh","pc":"+855"},{"id":"37","n":"Cameroon (+237)","i":"cm","pc":"+237"},{"id":"38","n":"Canada (+1)","i":"ca","pc":"+1"},{"id":"39","n":"Cape Verde (+238)","i":"cv","pc":"+238"},{"id":"40","n":"Cayman Islands (+1345)","i":"ky","pc":"+1345"},{"id":"41","n":"Central African Republic (+236)","i":"cf","pc":"+236"},{"id":"42","n":"Chad (+235)","i":"td","pc":"+235"},{"id":"43","n":"Chile (+56)","i":"cl","pc":"+56"},{"id":"44","n":"China (+86)","i":"cn","pc":"+86"},{"id":"45","n":"Christmas Island (+61)","i":"cx","pc":"+61"},{"id":"46","n":"Cocos (Keeling) Islands (+672)","i":"cc","pc":"+672"},{"id":"47","n":"Colombia (+57)","i":"co","pc":"+57"},{"id":"48","n":"Comoros (+269)","i":"km","pc":"+269"},{"id":"49","n":"Republic Of The Congo (+242)","i":"cg","pc":"+242"},{"id":"50","n":"Democratic Republic Of The Congo (+242)","i":"cd","pc":"+242"},{"id":"51","n":"Cook Islands (+682)","i":"ck","pc":"+682"},{"id":"52","n":"Costa Rica (+506)","i":"cr","pc":"+506"},{"id":"53","n":"Cote D'Ivoire (Ivory Coast) (+225)","i":"ci","pc":"+225"},{"id":"54","n":"Croatia (Hrvatska) (+385)","i":"hr","pc":"+385"},{"id":"55","n":"Cuba (+53)","i":"cu","pc":"+53"},{"id":"56","n":"Cyprus (+357)","i":"cy","pc":"+357"},{"id":"57","n":"Czech Republic (+420)","i":"cz","pc":"+420"},{"id":"58","n":"Denmark (+45)","i":"dk","pc":"+45"},{"id":"59","n":"Djibouti (+253)","i":"dj","pc":"+253"},{"id":"60","n":"Dominica (+1767)","i":"dm","pc":"+1767"},{"id":"61","n":"Dominican Republic (+1809)","i":"do","pc":"+1809"},{"id":"62","n":"East Timor (+670)","i":"tp","pc":"+670"},{"id":"63","n":"Ecuador (+593)","i":"ec","pc":"+593"},{"id":"64","n":"Egypt (+20)","i":"eg","pc":"+20"},{"id":"65","n":"El Salvador (+503)","i":"sv","pc":"+503"},{"id":"66","n":"Equatorial Guinea (+240)","i":"gq","pc":"+240"},{"id":"67","n":"Eritrea (+291)","i":"er","pc":"+291"},{"id":"68","n":"Estonia (+372)","i":"ee","pc":"+372"},{"id":"69","n":"Ethiopia (+251)","i":"et","pc":"+251"},{"id":"70","n":"External Territories of Australia (+61)","i":"xa","pc":"+61"},{"id":"71","n":"Falkland Islands (+500)","i":"fk","pc":"+500"},{"id":"72","n":"Faroe Islands (+298)","i":"fo","pc":"+298"},{"id":"73","n":"Fiji Islands (+679)","i":"fj","pc":"+679"},{"id":"74","n":"Finland (+358)","i":"fi","pc":"+358"},{"id":"75","n":"France (+33)","i":"fr","pc":"+33"},{"id":"76","n":"French Guiana (+594)","i":"gf","pc":"+594"},{"id":"77","n":"French Polynesia (+689)","i":"pf","pc":"+689"},{"id":"78","n":"French Southern Territories (+0)","i":"tf","pc":"+0"},{"id":"79","n":"Gabon (+241)","i":"ga","pc":"+241"},{"id":"80","n":"Gambia The (+220)","i":"gm","pc":"+220"},{"id":"81","n":"Georgia (+995)","i":"ge","pc":"+995"},{"id":"82","n":"Germany (+49)","i":"de","pc":"+49"},{"id":"83","n":"Ghana (+233)","i":"gh","pc":"+233"},{"id":"84","n":"Gibraltar (+350)","i":"gi","pc":"+350"},{"id":"85","n":"Greece (+30)","i":"gr","pc":"+30"},{"id":"86","n":"Greenland (+299)","i":"gl","pc":"+299"},{"id":"87","n":"Grenada (+1473)","i":"gd","pc":"+1473"},{"id":"88","n":"Guadeloupe (+590)","i":"gp","pc":"+590"},{"id":"89","n":"Guam (+1671)","i":"gu","pc":"+1671"},{"id":"90","n":"Guatemala (+502)","i":"gt","pc":"+502"},{"id":"91","n":"Guernsey and Alderney (+44)","i":"xu","pc":"+44"},{"id":"92","n":"Guinea (+224)","i":"gn","pc":"+224"},{"id":"93","n":"Guinea-Bissau (+245)","i":"gw","pc":"+245"},{"id":"94","n":"Guyana (+592)","i":"gy","pc":"+592"},{"id":"95","n":"Haiti (+509)","i":"ht","pc":"+509"},{"id":"96","n":"Heard and McDonald Islands (+0)","i":"hm","pc":"+0"},{"id":"97","n":"Honduras (+504)","i":"hn","pc":"+504"},{"id":"98","n":"Hong Kong S.A.R. (+852)","i":"hk","pc":"+852"},{"id":"99","n":"Hungary (+36)","i":"hu","pc":"+36"},{"id":"100","n":"Iceland (+354)","i":"is","pc":"+354"},{"id":"101","n":"India (+91)","i":"in","pc":"+91"},{"id":"102","n":"Indonesia (+62)","i":"id","pc":"+62"},{"id":"103","n":"Iran (+98)","i":"ir","pc":"+98"},{"id":"104","n":"Iraq (+964)","i":"iq","pc":"+964"},{"id":"105","n":"Ireland (+353)","i":"ie","pc":"+353"},{"id":"106","n":"Israel (+972)","i":"il","pc":"+972"},{"id":"107","n":"Italy (+39)","i":"it","pc":"+39"},{"id":"108","n":"Jamaica (+1876)","i":"jm","pc":"+1876"},{"id":"109","n":"Japan (+81)","i":"jp","pc":"+81"},{"id":"110","n":"Jersey (+44)","i":"xj","pc":"+44"},{"id":"111","n":"Jordan (+962)","i":"jo","pc":"+962"},{"id":"112","n":"Kazakhstan (+7)","i":"kz","pc":"+7"},{"id":"113","n":"Kenya (+254)","i":"ke","pc":"+254"},{"id":"114","n":"Kiribati (+686)","i":"ki","pc":"+686"},{"id":"115","n":"Korea North (+850)","i":"kp","pc":"+850"},{"id":"116","n":"Korea South (+82)","i":"kr","pc":"+82"},{"id":"117","n":"Kuwait (+965)","i":"kw","pc":"+965"},{"id":"118","n":"Kyrgyzstan (+996)","i":"kg","pc":"+996"},{"id":"119","n":"Laos (+856)","i":"la","pc":"+856"},{"id":"120","n":"Latvia (+371)","i":"lv","pc":"+371"},{"id":"121","n":"Lebanon (+961)","i":"lb","pc":"+961"},{"id":"122","n":"Lesotho (+266)","i":"ls","pc":"+266"},{"id":"123","n":"Liberia (+231)","i":"lr","pc":"+231"},{"id":"124","n":"Libya (+218)","i":"ly","pc":"+218"},{"id":"125","n":"Liechtenstein (+423)","i":"li","pc":"+423"},{"id":"126","n":"Lithuania (+370)","i":"lt","pc":"+370"},{"id":"127","n":"Luxembourg (+352)","i":"lu","pc":"+352"},{"id":"128","n":"Macau S.A.R. (+853)","i":"mo","pc":"+853"},{"id":"129","n":"Macedonia (+389)","i":"mk","pc":"+389"},{"id":"130","n":"Madagascar (+261)","i":"mg","pc":"+261"},{"id":"131","n":"Malawi (+265)","i":"mw","pc":"+265"},{"id":"132","n":"Malaysia (+60)","i":"my","pc":"+60"},{"id":"133","n":"Maldives (+960)","i":"mv","pc":"+960"},{"id":"134","n":"Mali (+223)","i":"ml","pc":"+223"},{"id":"135","n":"Malta (+356)","i":"mt","pc":"+356"},{"id":"136","n":"Man (Isle of) (+44)","i":"xm","pc":"+44"},{"id":"137","n":"Marshall Islands (+692)","i":"mh","pc":"+692"},{"id":"138","n":"Martinique (+596)","i":"mq","pc":"+596"},{"id":"139","n":"Mauritania (+222)","i":"mr","pc":"+222"},{"id":"140","n":"Mauritius (+230)","i":"mu","pc":"+230"},{"id":"141","n":"Mayotte (+269)","i":"yt","pc":"+269"},{"id":"142","n":"Mexico (+52)","i":"mx","pc":"+52"},{"id":"143","n":"Micronesia (+691)","i":"fm","pc":"+691"},{"id":"144","n":"Moldova (+373)","i":"md","pc":"+373"},{"id":"145","n":"Monaco (+377)","i":"mc","pc":"+377"},{"id":"146","n":"Mongolia (+976)","i":"mn","pc":"+976"},{"id":"147","n":"Montserrat (+1664)","i":"ms","pc":"+1664"},{"id":"148","n":"Morocco (+212)","i":"ma","pc":"+212"},{"id":"149","n":"Mozambique (+258)","i":"mz","pc":"+258"},{"id":"150","n":"Myanmar (+95)","i":"mm","pc":"+95"},{"id":"151","n":"Namibia (+264)","i":"na","pc":"+264"},{"id":"152","n":"Nauru (+674)","i":"nr","pc":"+674"},{"id":"153","n":"Nepal (+977)","i":"np","pc":"+977"},{"id":"154","n":"Netherlands Antilles (+599)","i":"an","pc":"+599"},{"id":"155","n":"Netherlands The (+31)","i":"nl","pc":"+31"},{"id":"156","n":"New Caledonia (+687)","i":"nc","pc":"+687"},{"id":"157","n":"New Zealand (+64)","i":"nz","pc":"+64"},{"id":"158","n":"Nicaragua (+505)","i":"ni","pc":"+505"},{"id":"159","n":"Niger (+227)","i":"ne","pc":"+227"},{"id":"160","n":"Nigeria (+234)","i":"ng","pc":"+234"},{"id":"161","n":"Niue (+683)","i":"nu","pc":"+683"},{"id":"162","n":"Norfolk Island (+672)","i":"nf","pc":"+672"},{"id":"163","n":"Northern Mariana Islands (+1670)","i":"mp","pc":"+1670"},{"id":"164","n":"Norway (+47)","i":"no","pc":"+47"},{"id":"165","n":"Oman (+968)","i":"om","pc":"+968"},{"id":"166","n":"Pakistan (+92)","i":"pk","pc":"+92"},{"id":"167","n":"Palau (+680)","i":"pw","pc":"+680"},{"id":"168","n":"Palestinian Territory Occupied (+970)","i":"ps","pc":"+970"},{"id":"169","n":"Panama (+507)","i":"pa","pc":"+507"},{"id":"170","n":"Papua new Guinea (+675)","i":"pg","pc":"+675"},{"id":"171","n":"Paraguay (+595)","i":"py","pc":"+595"},{"id":"172","n":"Peru (+51)","i":"pe","pc":"+51"},{"id":"173","n":"Philippines (+63)","i":"ph","pc":"+63"},{"id":"174","n":"Pitcairn Island (+0)","i":"pn","pc":"+0"},{"id":"175","n":"Poland (+48)","i":"pl","pc":"+48"},{"id":"176","n":"Portugal (+351)","i":"pt","pc":"+351"},{"id":"177","n":"Puerto Rico (+1787)","i":"pr","pc":"+1787"},{"id":"178","n":"Qatar (+974)","i":"qa","pc":"+974"},{"id":"179","n":"Reunion (+262)","i":"re","pc":"+262"},{"id":"180","n":"Romania (+40)","i":"ro","pc":"+40"},{"id":"181","n":"Russia (+70)","i":"ru","pc":"+70"},{"id":"182","n":"Rwanda (+250)","i":"rw","pc":"+250"},{"id":"183","n":"Saint Helena (+290)","i":"sh","pc":"+290"},{"id":"184","n":"Saint Kitts And Nevis (+1869)","i":"kn","pc":"+1869"},{"id":"185","n":"Saint Lucia (+1758)","i":"lc","pc":"+1758"},{"id":"186","n":"Saint Pierre and Miquelon (+508)","i":"pm","pc":"+508"},{"id":"187","n":"Saint Vincent And The Grenadines (+1784)","i":"vc","pc":"+1784"},{"id":"188","n":"Samoa (+684)","i":"ws","pc":"+684"},{"id":"189","n":"San Marino (+378)","i":"sm","pc":"+378"},{"id":"190","n":"Sao Tome and Principe (+239)","i":"st","pc":"+239"},{"id":"191","n":"Saudi Arabia (+966)","i":"sa","pc":"+966"},{"id":"192","n":"Senegal (+221)","i":"sn","pc":"+221"},{"id":"193","n":"Serbia (+381)","i":"rs","pc":"+381"},{"id":"194","n":"Seychelles (+248)","i":"sc","pc":"+248"},{"id":"195","n":"Sierra Leone (+232)","i":"sl","pc":"+232"},{"id":"196","n":"Singapore (+65)","i":"sg","pc":"+65"},{"id":"197","n":"Slovakia (+421)","i":"sk","pc":"+421"},{"id":"198","n":"Slovenia (+386)","i":"si","pc":"+386"},{"id":"199","n":"Smaller Territories of the UK (+44)","i":"xg","pc":"+44"},{"id":"200","n":"Solomon Islands (+677)","i":"sb","pc":"+677"},{"id":"201","n":"Somalia (+252)","i":"so","pc":"+252"},{"id":"202","n":"South Africa (+27)","i":"za","pc":"+27"},{"id":"203","n":"South Georgia (+0)","i":"gs","pc":"+0"},{"id":"204","n":"South Sudan (+211)","i":"ss","pc":"+211"},{"id":"205","n":"Spain (+34)","i":"es","pc":"+34"},{"id":"206","n":"Sri Lanka (+94)","i":"lk","pc":"+94"},{"id":"207","n":"Sudan (+249)","i":"sd","pc":"+249"},{"id":"208","n":"Suriname (+597)","i":"sr","pc":"+597"},{"id":"209","n":"Svalbard And Jan Mayen Islands (+47)","i":"sj","pc":"+47"},{"id":"210","n":"Swaziland (+268)","i":"sz","pc":"+268"},{"id":"211","n":"Sweden (+46)","i":"se","pc":"+46"},{"id":"212","n":"Switzerland (+41)","i":"ch","pc":"+41"},{"id":"213","n":"Syria (+963)","i":"sy","pc":"+963"},{"id":"214","n":"Taiwan (+886)","i":"tw","pc":"+886"},{"id":"215","n":"Tajikistan (+992)","i":"tj","pc":"+992"},{"id":"216","n":"Tanzania (+255)","i":"tz","pc":"+255"},{"id":"217","n":"Thailand (+66)","i":"th","pc":"+66"},{"id":"218","n":"Togo (+228)","i":"tg","pc":"+228"},{"id":"219","n":"Tokelau (+690)","i":"tk","pc":"+690"},{"id":"220","n":"Tonga (+676)","i":"to","pc":"+676"},{"id":"221","n":"Trinidad And Tobago (+1868)","i":"tt","pc":"+1868"},{"id":"222","n":"Tunisia (+216)","i":"tn","pc":"+216"},{"id":"223","n":"Turkey (+90)","i":"tr","pc":"+90"},{"id":"224","n":"Turkmenistan (+7370)","i":"tm","pc":"+7370"},{"id":"225","n":"Turks And Caicos Islands (+1649)","i":"tc","pc":"+1649"},{"id":"226","n":"Tuvalu (+688)","i":"tv","pc":"+688"},{"id":"227","n":"Uganda (+256)","i":"ug","pc":"+256"},{"id":"228","n":"Ukraine (+380)","i":"ua","pc":"+380"},{"id":"229","n":"United Arab Emirates (+971)","i":"ae","pc":"+971"},{"id":"230","n":"United Kingdom (+44)","i":"gb","pc":"+44"},{"id":"231","n":"United States (+1)","i":"us","pc":"+1"},{"id":"232","n":"United States Minor Outlying Islands (+1)","i":"um","pc":"+1"},{"id":"233","n":"Uruguay (+598)","i":"uy","pc":"+598"},{"id":"234","n":"Uzbekistan (+998)","i":"uz","pc":"+998"},{"id":"235","n":"Vanuatu (+678)","i":"vu","pc":"+678"},{"id":"236","n":"Vatican City State (Holy See) (+39)","i":"va","pc":"+39"},{"id":"237","n":"Venezuela (+58)","i":"ve","pc":"+58"},{"id":"238","n":"Vietnam (+84)","i":"vn","pc":"+84"},{"id":"239","n":"Virgin Islands (British) (+1284)","i":"vg","pc":"+1284"},{"id":"240","n":"Virgin Islands (US) (+1340)","i":"vi","pc":"+1340"},{"id":"241","n":"Wallis And Futuna Islands (+681)","i":"wf","pc":"+681"},{"id":"242","n":"Western Sahara (+212)","i":"eh","pc":"+212"},{"id":"243","n":"Yemen (+967)","i":"ye","pc":"+967"},{"id":"244","n":"Yugoslavia (+38)","i":"yu","pc":"+38"},{"id":"245","n":"Zambia (+260)","i":"zm","pc":"+260"},{"id":"246","n":"Zimbabwe (+263)","i":"zw","pc":"+263"}], function(i, c) {
		c.name = c.n;
		c.iso2 = c.i;
		delete c.n;
		delete c.i;
	});
});
