<?php
/**
 * SVG symbol library. Included once per page from header.php.
 * Symbols are referenced via <use href="#i-..."/> (see ameer_icon()).
 *
 * @package Ameer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
	<defs>
		<!-- balloon -->
		<symbol id="i-balloon" viewBox="0 0 80 110">
			<defs>
				<radialGradient id="balloonGrad" cx="35%" cy="30%">
					<stop offset="0%" stop-color="#FFB5A8"/>
					<stop offset="60%" stop-color="#FA6255"/>
					<stop offset="100%" stop-color="#C73F30"/>
				</radialGradient>
			</defs>
			<ellipse cx="40" cy="42" rx="32" ry="38" fill="url(#balloonGrad)"/>
			<ellipse cx="28" cy="28" rx="8" ry="12" fill="#FFE6E0" opacity="0.6"/>
			<path d="M37,78 L43,78 L40,86 Z" fill="#9D3A2F"/>
			<path d="M40,86 Q34,94 40,100 T40,108" stroke="#3A3530" stroke-width="1.5" fill="none" stroke-linecap="round"/>
		</symbol>
		<!-- cloud -->
		<symbol id="i-cloud" viewBox="0 0 160 80">
			<defs>
				<linearGradient id="cloudGrad" x1="0" y1="0" x2="0" y2="1">
					<stop offset="0%" stop-color="#ffffff"/>
					<stop offset="100%" stop-color="#F4ECDD"/>
				</linearGradient>
			</defs>
			<ellipse cx="40" cy="50" rx="32" ry="22" fill="url(#cloudGrad)"/>
			<ellipse cx="80" cy="38" rx="38" ry="28" fill="url(#cloudGrad)"/>
			<ellipse cx="120" cy="50" rx="32" ry="22" fill="url(#cloudGrad)"/>
			<ellipse cx="60" cy="56" rx="20" ry="14" fill="#ffffff"/>
			<ellipse cx="100" cy="56" rx="22" ry="14" fill="#ffffff"/>
		</symbol>
		<!-- sun -->
		<symbol id="i-sun" viewBox="0 0 160 160">
			<defs>
				<radialGradient id="sunGrad" cx="40%" cy="40%">
					<stop offset="0%" stop-color="#FFF1AE"/>
					<stop offset="60%" stop-color="#FDCB46"/>
					<stop offset="100%" stop-color="#E5A823"/>
				</radialGradient>
			</defs>
			<g stroke="#FDCB46" stroke-width="5" stroke-linecap="round">
				<line x1="80" y1="6" x2="80" y2="24"/>
				<line x1="80" y1="136" x2="80" y2="154"/>
				<line x1="6" y1="80" x2="24" y2="80"/>
				<line x1="136" y1="80" x2="154" y2="80"/>
				<line x1="28" y1="28" x2="40" y2="40"/>
				<line x1="120" y1="120" x2="132" y2="132"/>
				<line x1="132" y1="28" x2="120" y2="40"/>
				<line x1="40" y1="120" x2="28" y2="132"/>
			</g>
			<circle cx="80" cy="80" r="45" fill="url(#sunGrad)"/>
			<circle cx="68" cy="68" r="10" fill="#FFF1AE" opacity="0.55"/>
		</symbol>
		<!-- star sparkle -->
		<symbol id="i-sparkle" viewBox="0 0 30 30">
			<path d="M15,3 L17,13 L27,15 L17,17 L15,27 L13,17 L3,15 L13,13 Z" fill="currentColor"/>
		</symbol>
		<!-- quote mark -->
		<symbol id="i-quote" viewBox="0 0 40 36">
			<path d="M6,30 L6,18 C6,10 12,4 18,4 L18,10 C14,10 12,14 12,18 L18,18 L18,30 Z M22,30 L22,18 C22,10 28,4 34,4 L34,10 C30,10 28,14 28,18 L34,18 L34,30 Z" fill="currentColor" opacity="0.18"/>
		</symbol>
		<!-- pine tree -->
		<symbol id="i-tree" viewBox="0 0 60 90">
			<rect x="26" y="68" width="8" height="20" fill="#7A5A3F"/>
			<polygon points="30,4 8,40 18,40 6,62 22,62 14,80 46,80 38,62 54,62 42,40 52,40" fill="#5B8E45" stroke="#3E6B2E" stroke-width="1.5" stroke-linejoin="round"/>
			<circle cx="22" cy="36" r="2" fill="#FA6255"/>
			<circle cx="40" cy="54" r="2" fill="#FDCB46"/>
			<circle cx="30" cy="70" r="2" fill="#FA6255"/>
		</symbol>
		<!-- sail boat -->
		<symbol id="i-boat" viewBox="0 0 80 70">
			<path d="M4,46 L76,46 L66,62 L14,62 Z" fill="#FA6255" stroke="#9D3A2F" stroke-width="2" stroke-linejoin="round"/>
			<rect x="38" y="10" width="3" height="38" fill="#7A5A3F"/>
			<path d="M40,12 L40,44 L66,44 Z" fill="white" stroke="#3A3530" stroke-width="1.5" stroke-linejoin="round"/>
			<path d="M40,12 L40,38 L18,38 Z" fill="#FDCB46" stroke="#3A3530" stroke-width="1.5" stroke-linejoin="round"/>
			<circle cx="40" cy="8" r="3" fill="#FA6255"/>
		</symbol>
		<!-- bird (simple) -->
		<symbol id="i-bird" viewBox="0 0 60 28">
			<path d="M2,16 Q14,2 26,16 Q34,8 42,16 Q50,8 58,16" stroke="#2A3A56" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
		</symbol>
		<!-- kite -->
		<symbol id="i-kite" viewBox="0 0 50 90">
			<polygon points="25,4 44,30 25,56 6,30" fill="#FA6255" stroke="#3A3530" stroke-width="1.5" stroke-linejoin="round"/>
			<line x1="25" y1="4" x2="25" y2="56" stroke="#3A3530" stroke-width="1"/>
			<line x1="6" y1="30" x2="44" y2="30" stroke="#3A3530" stroke-width="1"/>
			<path d="M25,56 Q22,64 26,70 T24,84" stroke="#3A3530" stroke-width="1.2" fill="none"/>
			<path d="M22,66 L18,68 M27,72 L23,74 M22,80 L18,82" stroke="#FDCB46" stroke-width="2"/>
		</symbol>
		<!-- mini cloud (compact) -->
		<symbol id="i-cloud-mini" viewBox="0 0 80 40">
			<ellipse cx="20" cy="26" rx="16" ry="12" fill="white"/>
			<ellipse cx="42" cy="20" rx="20" ry="15" fill="white"/>
			<ellipse cx="62" cy="26" rx="16" ry="12" fill="white"/>
		</symbol>
		<!-- lorry / cute delivery truck (facing right) -->
		<symbol id="i-lorry" viewBox="0 0 160 80">
			<ellipse cx="80" cy="72" rx="68" ry="4.5" fill="rgba(45,38,32,0.12)"/>
			<rect x="6" y="52" width="6" height="5" rx="2.5" fill="#5C4F44"/>
			<rect x="4" y="56" width="10" height="3" rx="1.5" fill="#3A3530"/>
			<rect x="10" y="36" width="5" height="7" rx="2.5" fill="#C73F30"/>
			<rect x="8" y="54" width="12" height="6" rx="3" fill="#2D2620"/>
			<rect x="14" y="16" width="76" height="42" rx="8" fill="#FA6255"/>
			<rect x="16" y="18" width="72" height="3" rx="1.5" fill="rgba(255,255,255,0.2)"/>
			<line x1="32" y1="20" x2="32" y2="54" stroke="rgba(0,0,0,0.06)" stroke-width="1.5"/>
			<line x1="52" y1="20" x2="52" y2="54" stroke="rgba(0,0,0,0.06)" stroke-width="1.5"/>
			<line x1="72" y1="20" x2="72" y2="54" stroke="rgba(0,0,0,0.06)" stroke-width="1.5"/>
			<rect x="18" y="22" width="12" height="12" rx="4" fill="#FFF9E6" opacity="0.9"/>
			<rect x="34" y="22" width="15" height="12" rx="4" fill="#FFF9E6" opacity="0.9"/>
			<rect x="53" y="22" width="15" height="12" rx="4" fill="#FFF9E6" opacity="0.9"/>
			<line x1="20" y1="24" x2="20" y2="32" stroke="white" stroke-width="1.5" opacity="0.4"/>
			<line x1="37" y1="24" x2="37" y2="32" stroke="white" stroke-width="1.5" opacity="0.4"/>
			<line x1="56" y1="24" x2="56" y2="32" stroke="white" stroke-width="1.5" opacity="0.4"/>
			<rect x="14" y="40" width="76" height="6" fill="#FDCB46"/>
			<rect x="14" y="40" width="76" height="2" fill="rgba(255,255,255,0.35)"/>
			<path d="M78,56 L78,24 Q78,10 92,10 L128,10 L142,18 L142,56 Z" fill="#91BEF8"/>
			<path d="M80,56 L80,24 Q80,13 92,13 L126,13 L138,19 L138,56" fill="none" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
			<rect x="94" y="14" width="38" height="10" rx="6" fill="#6A9EDB"/>
			<rect x="98" y="16" width="30" height="3" rx="1.5" fill="rgba(255,255,255,0.3)"/>
			<path d="M112,20 L128,20 L128,34 Q118,30 112,34 Z" fill="#FFF9E6" opacity="0.95"/>
			<line x1="116" y1="22" x2="116" y2="31" stroke="white" stroke-width="1.5"/>
			<rect x="94" y="20" width="14" height="16" rx="5" fill="#FFF9E6" opacity="0.9"/>
			<line x1="98" y1="22" x2="98" y2="34" stroke="white" stroke-width="1.5" opacity="0.4"/>
			<rect x="96" y="44" width="8" height="2.5" rx="1.2" fill="rgba(255,255,255,0.6)"/>
			<line x1="110" y1="28" x2="110" y2="56" stroke="#5A9ECB" stroke-width="1.5"/>
			<rect x="138" y="30" width="5" height="18" rx="2.5" fill="#2D2620"/>
			<line x1="139" y1="34" x2="142" y2="34" stroke="#5C4F44" stroke-width="0.8"/>
			<line x1="139" y1="38" x2="142" y2="38" stroke="#5C4F44" stroke-width="0.8"/>
			<line x1="139" y1="42" x2="142" y2="42" stroke="#5C4F44" stroke-width="0.8"/>
			<rect x="132" y="50" width="12" height="8" rx="4" fill="#2D2620"/>
			<circle cx="140" cy="44" r="5" fill="#FDCB46"/>
			<circle cx="140" cy="44" r="2.5" fill="#FFF9E6"/>
			<rect x="134" y="32" width="3" height="5" rx="1.5" fill="#FDCB46"/>
			<circle cx="44" cy="60" r="10" fill="#2D2620"/>
			<circle cx="44" cy="60" r="7" fill="#3A3530"/>
			<circle cx="44" cy="60" r="4.5" fill="#FFF9E6"/>
			<circle cx="44" cy="60" r="2" fill="#5C4F44"/>
			<circle cx="90" cy="60" r="10" fill="#2D2620"/>
			<circle cx="90" cy="60" r="7" fill="#3A3530"/>
			<circle cx="90" cy="60" r="4.5" fill="#FFF9E6"/>
			<circle cx="90" cy="60" r="2" fill="#5C4F44"/>
			<circle cx="126" cy="60" r="10" fill="#2D2620"/>
			<circle cx="126" cy="60" r="7" fill="#3A3530"/>
			<circle cx="126" cy="60" r="4.5" fill="#FFF9E6"/>
			<circle cx="126" cy="60" r="2" fill="#5C4F44"/>
			<rect x="130" y="20" width="6" height="5" rx="2" fill="#5C4F44"/>
			<line x1="130" y1="22" x2="124" y2="22" stroke="#5C4F44" stroke-width="2"/>
			<line x1="100" y1="12" x2="100" y2="2" stroke="#2D2620" stroke-width="1.5"/>
			<polygon points="100,2 110,5 100,8" fill="#FA6255"/>
		</symbol>
	</defs>
</svg>
