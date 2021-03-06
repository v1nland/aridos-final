var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
/**
 * @module ol/Overlay
 */
import MapEventType from './MapEventType.js';
import BaseObject, { getChangeEventType } from './Object.js';
import OverlayPositioning from './OverlayPositioning.js';
import { CLASS_SELECTABLE } from './css.js';
import { removeNode, removeChildren, outerWidth, outerHeight } from './dom.js';
import { listen, unlistenByKey } from './events.js';
import { containsExtent } from './extent.js';
/**
 * @typedef {Object} Options
 * @property {number|string} [id] Set the overlay id. The overlay id can be used
 * with the {@link module:ol/Map~Map#getOverlayById} method.
 * @property {HTMLElement} [element] The overlay element.
 * @property {Array<number>} [offset=[0, 0]] Offsets in pixels used when positioning
 * the overlay. The first element in the
 * array is the horizontal offset. A positive value shifts the overlay right.
 * The second element in the array is the vertical offset. A positive value
 * shifts the overlay down.
 * @property {import("./coordinate.js").Coordinate} [position] The overlay position
 * in map projection.
 * @property {OverlayPositioning} [positioning='top-left'] Defines how
 * the overlay is actually positioned with respect to its `position` property.
 * Possible values are `'bottom-left'`, `'bottom-center'`, `'bottom-right'`,
 * `'center-left'`, `'center-center'`, `'center-right'`, `'top-left'`,
 * `'top-center'`, and `'top-right'`.
 * @property {boolean} [stopEvent=true] Whether event propagation to the map
 * viewport should be stopped. If `true` the overlay is placed in the same
 * container as that of the controls (CSS class name
 * `ol-overlaycontainer-stopevent`); if `false` it is placed in the container
 * with CSS class name specified by the `className` property.
 * @property {boolean} [insertFirst=true] Whether the overlay is inserted first
 * in the overlay container, or appended. If the overlay is placed in the same
 * container as that of the controls (see the `stopEvent` option) you will
 * probably set `insertFirst` to `true` so the overlay is displayed below the
 * controls.
 * @property {boolean} [autoPan=false] If set to `true` the map is panned when
 * calling `setPosition`, so that the overlay is entirely visible in the current
 * viewport.
 * @property {PanOptions} [autoPanAnimation] The
 * animation options used to pan the overlay into view. This animation is only
 * used when `autoPan` is enabled. A `duration` and `easing` may be provided to
 * customize the animation.
 * @property {number} [autoPanMargin=20] The margin (in pixels) between the
 * overlay and the borders of the map when autopanning.
 * @property {string} [className='ol-overlay-container ol-selectable'] CSS class
 * name.
 */
/**
 * @typedef {Object} PanOptions
 * @property {number} [duration=1000] The duration of the animation in
 * milliseconds.
 * @property {function(number):number} [easing] The easing function to use. Can
 * be one from {@link module:ol/easing} or a custom function.
 * Default is {@link module:ol/easing~inAndOut}.
 */
/**
 * @enum {string}
 * @protected
 */
var Property = {
    ELEMENT: 'element',
    MAP: 'map',
    OFFSET: 'offset',
    POSITION: 'position',
    POSITIONING: 'positioning'
};
/**
 * @classdesc
 * An element to be displayed over the map and attached to a single map
 * location.  Like {@link module:ol/control/Control~Control}, Overlays are
 * visible widgets. Unlike Controls, they are not in a fixed position on the
 * screen, but are tied to a geographical coordinate, so panning the map will
 * move an Overlay but not a Control.
 *
 * Example:
 *
 *     import Overlay from 'ol/Overlay';
 *
 *     var popup = new Overlay({
 *       element: document.getElementById('popup')
 *     });
 *     popup.setPosition(coordinate);
 *     map.addOverlay(popup);
 *
 * @api
 */
var Overlay = /** @class */ (function (_super) {
    __extends(Overlay, _super);
    /**
     * @param {Options} options Overlay options.
     */
    function Overlay(options) {
        var _this = _super.call(this) || this;
        /**
         * @protected
         * @type {Options}
         */
        _this.options = options;
        /**
         * @protected
         * @type {number|string|undefined}
         */
        _this.id = options.id;
        /**
         * @protected
         * @type {boolean}
         */
        _this.insertFirst = options.insertFirst !== undefined ?
            options.insertFirst : true;
        /**
         * @protected
         * @type {boolean}
         */
        _this.stopEvent = options.stopEvent !== undefined ? options.stopEvent : true;
        /**
         * @protected
         * @type {HTMLElement}
         */
        _this.element = document.createElement('div');
        _this.element.className = options.className !== undefined ?
            options.className : 'ol-overlay-container ' + CLASS_SELECTABLE;
        _this.element.style.position = 'absolute';
        /**
         * @protected
         * @type {boolean}
         */
        _this.autoPan = options.autoPan !== undefined ? options.autoPan : false;
        /**
         * @protected
         * @type {PanOptions}
         */
        _this.autoPanAnimation = options.autoPanAnimation || /** @type {PanOptions} */ ({});
        /**
         * @protected
         * @type {number}
         */
        _this.autoPanMargin = options.autoPanMargin !== undefined ?
            options.autoPanMargin : 20;
        /**
         * @protected
         * @type {{bottom_: string,
         *         left_: string,
         *         right_: string,
         *         top_: string,
         *         visible: boolean}}
         */
        _this.rendered = {
            bottom_: '',
            left_: '',
            right_: '',
            top_: '',
            visible: true
        };
        /**
         * @protected
         * @type {?import("./events.js").EventsKey}
         */
        _this.mapPostrenderListenerKey = null;
        _this.addEventListener(getChangeEventType(Property.ELEMENT), _this.handleElementChanged);
        _this.addEventListener(getChangeEventType(Property.MAP), _this.handleMapChanged);
        _this.addEventListener(getChangeEventType(Property.OFFSET), _this.handleOffsetChanged);
        _this.addEventListener(getChangeEventType(Property.POSITION), _this.handlePositionChanged);
        _this.addEventListener(getChangeEventType(Property.POSITIONING), _this.handlePositioningChanged);
        if (options.element !== undefined) {
            _this.setElement(options.element);
        }
        _this.setOffset(options.offset !== undefined ? options.offset : [0, 0]);
        _this.setPositioning(options.positioning !== undefined ?
            /** @type {OverlayPositioning} */ (options.positioning) :
            OverlayPositioning.TOP_LEFT);
        if (options.position !== undefined) {
            _this.setPosition(options.position);
        }
        return _this;
    }
    /**
     * Get the DOM element of this overlay.
     * @return {HTMLElement|undefined} The Element containing the overlay.
     * @observable
     * @api
     */
    Overlay.prototype.getElement = function () {
        return /** @type {HTMLElement|undefined} */ (this.get(Property.ELEMENT));
    };
    /**
     * Get the overlay identifier which is set on constructor.
     * @return {number|string|undefined} Id.
     * @api
     */
    Overlay.prototype.getId = function () {
        return this.id;
    };
    /**
     * Get the map associated with this overlay.
     * @return {import("./PluggableMap.js").default|undefined} The map that the
     * overlay is part of.
     * @observable
     * @api
     */
    Overlay.prototype.getMap = function () {
        return (
        /** @type {import("./PluggableMap.js").default|undefined} */ (this.get(Property.MAP)));
    };
    /**
     * Get the offset of this overlay.
     * @return {Array<number>} The offset.
     * @observable
     * @api
     */
    Overlay.prototype.getOffset = function () {
        return /** @type {Array<number>} */ (this.get(Property.OFFSET));
    };
    /**
     * Get the current position of this overlay.
     * @return {import("./coordinate.js").Coordinate|undefined} The spatial point that the overlay is
     *     anchored at.
     * @observable
     * @api
     */
    Overlay.prototype.getPosition = function () {
        return (
        /** @type {import("./coordinate.js").Coordinate|undefined} */ (this.get(Property.POSITION)));
    };
    /**
     * Get the current positioning of this overlay.
     * @return {OverlayPositioning} How the overlay is positioned
     *     relative to its point on the map.
     * @observable
     * @api
     */
    Overlay.prototype.getPositioning = function () {
        return (
        /** @type {OverlayPositioning} */ (this.get(Property.POSITIONING)));
    };
    /**
     * @protected
     */
    Overlay.prototype.handleElementChanged = function () {
        removeChildren(this.element);
        var element = this.getElement();
        if (element) {
            this.element.appendChild(element);
        }
    };
    /**
     * @protected
     */
    Overlay.prototype.handleMapChanged = function () {
        if (this.mapPostrenderListenerKey) {
            removeNode(this.element);
            unlistenByKey(this.mapPostrenderListenerKey);
            this.mapPostrenderListenerKey = null;
        }
        var map = this.getMap();
        if (map) {
            this.mapPostrenderListenerKey = listen(map, MapEventType.POSTRENDER, this.render, this);
            this.updatePixelPosition();
            var container = this.stopEvent ?
                map.getOverlayContainerStopEvent() : map.getOverlayContainer();
            if (this.insertFirst) {
                container.insertBefore(this.element, container.childNodes[0] || null);
            }
            else {
                container.appendChild(this.element);
            }
        }
    };
    /**
     * @protected
     */
    Overlay.prototype.render = function () {
        this.updatePixelPosition();
    };
    /**
     * @protected
     */
    Overlay.prototype.handleOffsetChanged = function () {
        this.updatePixelPosition();
    };
    /**
     * @protected
     */
    Overlay.prototype.handlePositionChanged = function () {
        this.updatePixelPosition();
        if (this.get(Property.POSITION) && this.autoPan) {
            this.panIntoView();
        }
    };
    /**
     * @protected
     */
    Overlay.prototype.handlePositioningChanged = function () {
        this.updatePixelPosition();
    };
    /**
     * Set the DOM element to be associated with this overlay.
     * @param {HTMLElement|undefined} element The Element containing the overlay.
     * @observable
     * @api
     */
    Overlay.prototype.setElement = function (element) {
        this.set(Property.ELEMENT, element);
    };
    /**
     * Set the map to be associated with this overlay.
     * @param {import("./PluggableMap.js").default|undefined} map The map that the
     * overlay is part of.
     * @observable
     * @api
     */
    Overlay.prototype.setMap = function (map) {
        this.set(Property.MAP, map);
    };
    /**
     * Set the offset for this overlay.
     * @param {Array<number>} offset Offset.
     * @observable
     * @api
     */
    Overlay.prototype.setOffset = function (offset) {
        this.set(Property.OFFSET, offset);
    };
    /**
     * Set the position for this overlay. If the position is `undefined` the
     * overlay is hidden.
     * @param {import("./coordinate.js").Coordinate|undefined} position The spatial point that the overlay
     *     is anchored at.
     * @observable
     * @api
     */
    Overlay.prototype.setPosition = function (position) {
        this.set(Property.POSITION, position);
    };
    /**
     * Pan the map so that the overlay is entirely visible in the current viewport
     * (if necessary).
     * @protected
     */
    Overlay.prototype.panIntoView = function () {
        var map = this.getMap();
        if (!map || !map.getTargetElement()) {
            return;
        }
        var mapRect = this.getRect(map.getTargetElement(), map.getSize());
        var element = this.getElement();
        var overlayRect = this.getRect(element, [outerWidth(element), outerHeight(element)]);
        var margin = this.autoPanMargin;
        if (!containsExtent(mapRect, overlayRect)) {
            // the overlay is not completely inside the viewport, so pan the map
            var offsetLeft = overlayRect[0] - mapRect[0];
            var offsetRight = mapRect[2] - overlayRect[2];
            var offsetTop = overlayRect[1] - mapRect[1];
            var offsetBottom = mapRect[3] - overlayRect[3];
            var delta = [0, 0];
            if (offsetLeft < 0) {
                // move map to the left
                delta[0] = offsetLeft - margin;
            }
            else if (offsetRight < 0) {
                // move map to the right
                delta[0] = Math.abs(offsetRight) + margin;
            }
            if (offsetTop < 0) {
                // move map up
                delta[1] = offsetTop - margin;
            }
            else if (offsetBottom < 0) {
                // move map down
                delta[1] = Math.abs(offsetBottom) + margin;
            }
            if (delta[0] !== 0 || delta[1] !== 0) {
                var center = /** @type {import("./coordinate.js").Coordinate} */ (map.getView().getCenterInternal());
                var centerPx = map.getPixelFromCoordinateInternal(center);
                var newCenterPx = [
                    centerPx[0] + delta[0],
                    centerPx[1] + delta[1]
                ];
                map.getView().animateInternal({
                    center: map.getCoordinateFromPixelInternal(newCenterPx),
                    duration: this.autoPanAnimation.duration,
                    easing: this.autoPanAnimation.easing
                });
            }
        }
    };
    /**
     * Get the extent of an element relative to the document
     * @param {HTMLElement} element The element.
     * @param {import("./size.js").Size} size The size of the element.
     * @return {import("./extent.js").Extent} The extent.
     * @protected
     */
    Overlay.prototype.getRect = function (element, size) {
        var box = element.getBoundingClientRect();
        var offsetX = box.left + window.pageXOffset;
        var offsetY = box.top + window.pageYOffset;
        return [
            offsetX,
            offsetY,
            offsetX + size[0],
            offsetY + size[1]
        ];
    };
    /**
     * Set the positioning for this overlay.
     * @param {OverlayPositioning} positioning how the overlay is
     *     positioned relative to its point on the map.
     * @observable
     * @api
     */
    Overlay.prototype.setPositioning = function (positioning) {
        this.set(Property.POSITIONING, positioning);
    };
    /**
     * Modify the visibility of the element.
     * @param {boolean} visible Element visibility.
     * @protected
     */
    Overlay.prototype.setVisible = function (visible) {
        if (this.rendered.visible !== visible) {
            this.element.style.display = visible ? '' : 'none';
            this.rendered.visible = visible;
        }
    };
    /**
     * Update pixel position.
     * @protected
     */
    Overlay.prototype.updatePixelPosition = function () {
        var map = this.getMap();
        var position = this.getPosition();
        if (!map || !map.isRendered() || !position) {
            this.setVisible(false);
            return;
        }
        var pixel = map.getPixelFromCoordinate(position);
        var mapSize = map.getSize();
        this.updateRenderedPosition(pixel, mapSize);
    };
    /**
     * @param {import("./pixel.js").Pixel} pixel The pixel location.
     * @param {import("./size.js").Size|undefined} mapSize The map size.
     * @protected
     */
    Overlay.prototype.updateRenderedPosition = function (pixel, mapSize) {
        var style = this.element.style;
        var offset = this.getOffset();
        var positioning = this.getPositioning();
        this.setVisible(true);
        var offsetX = offset[0];
        var offsetY = offset[1];
        if (positioning == OverlayPositioning.BOTTOM_RIGHT ||
            positioning == OverlayPositioning.CENTER_RIGHT ||
            positioning == OverlayPositioning.TOP_RIGHT) {
            if (this.rendered.left_ !== '') {
                this.rendered.left_ = '';
                style.left = '';
            }
            var right = Math.round(mapSize[0] - pixel[0] - offsetX) + 'px';
            if (this.rendered.right_ != right) {
                this.rendered.right_ = right;
                style.right = right;
            }
        }
        else {
            if (this.rendered.right_ !== '') {
                this.rendered.right_ = '';
                style.right = '';
            }
            if (positioning == OverlayPositioning.BOTTOM_CENTER ||
                positioning == OverlayPositioning.CENTER_CENTER ||
                positioning == OverlayPositioning.TOP_CENTER) {
                offsetX -= this.element.offsetWidth / 2;
            }
            var left = Math.round(pixel[0] + offsetX) + 'px';
            if (this.rendered.left_ != left) {
                this.rendered.left_ = left;
                style.left = left;
            }
        }
        if (positioning == OverlayPositioning.BOTTOM_LEFT ||
            positioning == OverlayPositioning.BOTTOM_CENTER ||
            positioning == OverlayPositioning.BOTTOM_RIGHT) {
            if (this.rendered.top_ !== '') {
                this.rendered.top_ = '';
                style.top = '';
            }
            var bottom = Math.round(mapSize[1] - pixel[1] - offsetY) + 'px';
            if (this.rendered.bottom_ != bottom) {
                this.rendered.bottom_ = bottom;
                style.bottom = bottom;
            }
        }
        else {
            if (this.rendered.bottom_ !== '') {
                this.rendered.bottom_ = '';
                style.bottom = '';
            }
            if (positioning == OverlayPositioning.CENTER_LEFT ||
                positioning == OverlayPositioning.CENTER_CENTER ||
                positioning == OverlayPositioning.CENTER_RIGHT) {
                offsetY -= this.element.offsetHeight / 2;
            }
            var top_1 = Math.round(pixel[1] + offsetY) + 'px';
            if (this.rendered.top_ != top_1) {
                this.rendered.top_ = 'top';
                style.top = top_1;
            }
        }
    };
    /**
     * returns the options this Overlay has been created with
     * @return {Options} overlay options
     */
    Overlay.prototype.getOptions = function () {
        return this.options;
    };
    return Overlay;
}(BaseObject));
export default Overlay;
//# sourceMappingURL=Overlay.js.map