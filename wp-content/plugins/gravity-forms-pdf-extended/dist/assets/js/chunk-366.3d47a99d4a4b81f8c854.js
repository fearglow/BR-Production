"use strict";(self.webpackChunkgravity_pdf=self.webpackChunkgravity_pdf||[]).push([[366],{38:(e,t,r)=>{r.d(t,{Z:()=>p});r(752),r(6265),r(6544),r(1057);var n=r(5311),i=r.n(n),a=r(7294),s=r(5697),l=r.n(s);function o(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=typeof n)return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}
/**
 * Renders a message or error, with the option to self-clear itself
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */(e,"string");return"symbol"==typeof t?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}class c extends a.Component{constructor(){super(...arguments),o(this,"state",{visible:!0}),o(this,"shouldSetTimer",(()=>{this.props.dismissable&&this.setTimer()})),o(this,"setTimer",(()=>{this._timer=null!==this._timer?clearTimeout(this._timer):null,this._timer=setTimeout((()=>{i()(this._message).removeClass("inline").slideUp(400,(()=>{i()(this._message).removeAttr("style"),this.setState({visible:!1}),this._timer=null,this.props.dismissableCallback&&this.props.dismissableCallback()}))}),this.props.delay)})),o(this,"resetState",(()=>{this.setState({visible:!0}),this.shouldSetTimer()}))}componentDidMount(){this.shouldSetTimer()}componentDidUpdate(e,t){t.visible||this.resetState()}componentWillUnmount(){this.props.dismissable&&clearTimeout(this._timer)}render(){var{text:e,error:t}=this.props,r="notice inline";return t&&(r+=" error"),this.state.visible?a.createElement("div",{ref:e=>this._message=e,className:r},a.createElement("p",null,e)):a.createElement("div",null)}}o(c,"defaultProps",{delay:4e3,dismissable:!1}),o(c,"propTypes",{text:l().string.isRequired,error:l().bool,delay:l().number,dismissable:l().bool,dismissableCallback:l().func});const p=c},6152:(e,t,r)=>{r.d(t,{ZP:()=>u});r(752),r(6265),r(560),r(6544),r(1057);var n=r(7294),i=r(5697),a=r.n(i),s=r(6706),l=r(6550),o=r(4738);function c(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=typeof n)return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}
/**
 * Renders the button used to trigger the current active PDF template
 * On click it triggers our Redux action.
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */(e,"string");return"symbol"==typeof t?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}class p extends n.Component{constructor(){super(...arguments),c(this,"handleSelectTemplate",(e=>{e.preventDefault(),e.stopPropagation(),this.props.history.push(""),this.props.onTemplateSelect(this.props.template.id)}))}render(){return n.createElement("a",{onClick:this.handleSelectTemplate,href:"#",tabIndex:"150",className:"button activate","aria-label":this.props.buttonText+" "+GFPDF.template},this.props.buttonText)}}c(p,"propTypes",{history:a().object,onTemplateSelect:a().func,template:a().object,buttonText:a().string});const u=(0,l.EN)((0,s.$j)(null,(e=>({onTemplateSelect:t=>e((0,o.Un)(t))})))(p))},5439:(e,t,r)=>{r.d(t,{Z:()=>u});r(6544),r(1057);var n,i,a,s=r(7294),l=r(5697),o=r.n(l),c=r(2896);
/**
 * Renders our Advanced Template Selector container which is shared amongst the components
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */
class p extends s.Component{constructor(e){super(e),this.handleFocus=this.handleFocus.bind(this)}componentDidMount(){document.addEventListener("focus",this.handleFocus,!0),document.activeElement&&"wp-filter-search"!==document.activeElement.className&&this.container.focus()}componentWillUnmount(){document.removeEventListener("focus",this.handleFocus,!0)}handleFocus(e){this.container.contains(e.target)||(e.stopPropagation(),this.container.focus())}render(){var e=this.props.header,t=this.props.footer,r=this.props.children,n=this.props.closeRoute;return s.createElement("div",{ref:e=>this.container=e,tabIndex:"140"},s.createElement("div",{className:"backdrop theme-backdrop"}),s.createElement("div",{className:"container theme-wrap"},s.createElement("div",{className:"theme-header"},e,s.createElement(c.Z,{closeRoute:n})),s.createElement("div",{id:"gfpdf-template-container",className:"theme-about wp-clearfix theme-browser rendered"},r),t))}}n=p,i="propTypes",a={header:o().oneOfType([o().string,o().element]),footer:o().oneOfType([o().string,o().element]),children:o().node.isRequired,closeRoute:o().string},(i=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=typeof n)return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==typeof t?t:String(t)}(i))in n?Object.defineProperty(n,i,{value:a,enumerable:!0,configurable:!0,writable:!0}):n[i]=a;const u=p},5218:(e,t,r)=>{r.d(t,{$G:()=>m,PP:()=>s,S3:()=>c,VG:()=>l,ZA:()=>p,dk:()=>u});var n=r(7294),i=r(5697),a=r.n(i),s=e=>{var{isCurrentTemplate:t,label:r}=e;return t?n.createElement("span",{className:"current-label"},r):n.createElement("span",null)};s.propTypes={isCurrentTemplate:a().bool,label:a().string};var l=e=>{var{name:t,version:r,versionLabel:i}=e;return n.createElement("h2",{className:"theme-name"},t,n.createElement(o,{version:r,label:i}))};l.propTypes={name:a().string,version:a().string,versionLabel:a().string};var o=e=>{var{label:t,version:r}=e;return r?n.createElement("span",{className:"theme-version"},t,": ",r):n.createElement("span",null)};o.propTypes={label:a().string,version:a().string};var c=e=>{var{author:t,uri:r}=e;return r?n.createElement("p",{className:"theme-author"},n.createElement("a",{href:r},t)):n.createElement("p",{className:"theme-author"},t)};c.propTypes={author:a().string,uri:a().string};var p=e=>{var{label:t,group:r}=e;return n.createElement("p",{className:"theme-author"},n.createElement("strong",null,t,": ",r))};p.propTypes={label:a().string,group:a().string};var u=e=>{var{desc:t}=e;return n.createElement("p",{className:"theme-description"},t)};u.propTypes={desc:a().string};var m=e=>{var{label:t,tags:r}=e;return r?n.createElement("p",{className:"theme-tags"},n.createElement("span",null,t,":")," ",r):n.createElement("span",null)};m.propTypes={label:a().string,tags:a().string}},3223:(e,t,r)=>{r.d(t,{ZP:()=>o});r(4043),r(7872),r(7267),r(2003),r(8518),r(2826),r(6544),r(7409),r(5137),r(1057),r(560);var n=r(573);r(2320);function i(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function a(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?i(Object(r),!0).forEach((function(t){s(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function s(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=typeof e||null===e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var n=r.call(e,t||"default");if("object"!=typeof n)return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}
/**
 * Uses the Redux Reselect library to sort, filter and search our templates.
 * It also checks if the PDF templates are compatible with the current version of Gravity PDF
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2024, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */(e,"string");return"symbol"==typeof t?t:String(t)}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var l=e=>e.map((e=>{var t=e.required_pdf_version;return((e,t,r)=>{var n,i,a=0,s={dev:-6,alpha:-5,a:-5,beta:-4,b:-4,RC:-3,rc:-3,"#":-2,p:1,pl:1},l=function(e){return(e=(e=(""+e).replace(/[_\-+]/g,".")).replace(/([^.\d]+)/g,".$1.").replace(/\.{2,}/g,".")).length?e.split("."):[-8]},o=function(e){return e?isNaN(e)?s[e]||-7:parseInt(e,10):0};for(e=l(e),t=l(t),i=Math.max(e.length,t.length),n=0;n<i;n++)if(e[n]!==t[n]){if(e[n]=o(e[n]),t[n]=o(t[n]),e[n]<t[n]){a=-1;break}if(e[n]>t[n]){a=1;break}}if(!r)return a;switch(r){case">":case"gt":return a>0;case">=":case"ge":return a>=0;case"<=":case"le":return a<=0;case"===":case"=":case"eq":return 0===a;case"<>":case"!==":case"ne":return 0!==a;case"":case"<":case"lt":return a<0;default:return null}})(t,GFPDF.currentVersion,">")?a(a({},e),{},{compatible:!1,error:GFPDF.requiresGravityPdfVersion.replace(/%s/g,t),long_error:GFPDF.templateNotCompatibleWithGravityPdfVersion.replace(/%s/g,t)}):a(a({},e),{},{compatible:!0})}));const o=(0,n.P1)([e=>e.template.list,e=>e.template.search,e=>e.template.activeTemplate],((e,t,r)=>(e=l(e),t&&(e=((e,t)=>{e=(e=e.replace(/[-\/\\^$*+?.()|[\]{}]/g,"\\$&")).replace(/ /g,")(?=.*");var r=new RegExp("^(?=.*"+e+").+","i");return t.filter((e=>{var t=e.template.replace(/(<([^>]+)>)/gi,""),n=e.description.replace(/(<([^>]+)>)/gi,""),i=e.author.replace(/(<([^>]+)>)/gi,""),a=e.group.replace(/(<([^>]+)>)/gi,"");return r.test([t,e.id,a,n,i].toString())}))})(t,e)),((e,t)=>e.sort(((e,r)=>!0===e.new&&!0===e.new?0:!0===e.new?1:!0===r.new||t===e.id?-1:t===r.id?1:e.group<r.group?-1:e.group>r.group?1:e.template<r.template?-1:e.template>r.template?1:0)))(e,r))))}}]);