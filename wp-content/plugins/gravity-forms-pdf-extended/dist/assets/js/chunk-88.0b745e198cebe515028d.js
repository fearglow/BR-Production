"use strict";(self.webpackChunkgravity_pdf=self.webpackChunkgravity_pdf||[]).push([[88],{6088:(t,e,r)=>{r.r(e),r.d(e,{default:()=>l});r(752),r(6265),r(560),r(6544),r(1057);var n=r(7294),i=r(5697),a=r.n(i);function o(t,e,r){return(e=function(t){var e=function(t,e){if("object"!=typeof t||null===t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var n=r.call(t,e||"default");if("object"!=typeof n)return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===e?String:Number)(t)}
/**
 * Render the button used to option our Fancy PDF template selector
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */(t,"string");return"symbol"==typeof e?e:String(e)}(e))in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}class u extends n.Component{constructor(){super(...arguments),o(this,"handleClick",(t=>{t.preventDefault(),t.stopPropagation(),this.props.history.push("/template")}))}render(){return n.createElement("button",{type:"button",id:"fancy-template-selector",className:"button gfpdf-button",onClick:this.handleClick,ref:t=>this.button=t,"aria-label":GFPDF.manageTemplates},GFPDF.manage)}}o(u,"propTypes",{history:a().object});const l=u}}]);