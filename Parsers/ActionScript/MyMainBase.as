package com.benoitfreslon {
	import flash.display.MovieClip;
	import flash.events.Event;
	import flash.events.MouseEvent;
	import flash.media.SoundMixer;
	import flash.media.SoundTransform;
	import flash.net.navigateToURL;
	import flash.net.SharedObject;
	import flash.net.URLRequest;
	import flash.system.Capabilities;
	
	/**
	 * ...
	 * @author Benoît Freslon
	 */
	public class MyMainBase extends MyMovieClip {
		
		protected var _embedXML:XML;
		protected var _isLanguageLoaded:Boolean = false;
		protected var _isGameLoaded : Boolean = false;
		protected var _languageXML : XML = new XML ();
		protected var _languageLoader : MyTextLoader = new MyTextLoader ();
		
		public function MyMainBase () {
		}

		////////////////////////////////////////////////////////////// LANGUAGE
		protected function loadLanguage (embedClass:Class = null) : void {
			trace ("MyMain: loadLanguage " + languageURL,embedClass);
			_isLanguageLoaded = false;
			_languageXML = null;
			
			if (embedClass != null) {
				_embedXML = new XML (new embedClass());
				loadLocalLanguage ();
			} else {
				_languageLoader.load (languageURL , null , languageXMLLoaded , languageError);
			}
		}
		
		public function languageXMLLoaded (e : Event) : void {
			//trace( "MyMain: languageXMLLoaded", pEvt.target.data );
			try {
				loadLanguageXML (new XML (e.target.data))
			} catch (e) {
				trace ("Error language.xml on server " + e);
				languageError ();
			}
			_languageLoader = null;
		}
		
		public function loadLanguageXML (xmlLanguage : XML) : void {
			//trace('MyMain: loadLanguageXML', xmlLanguage);
			_languageXML = xmlLanguage;
			_languageXML.ignoreWhitespace = true;
			_languageXML.ignoreComments = true;
			arrAllLanguage = ["en"];
			for each (var xml : XML in _languageXML.children ()) {
				if (xml.@language != "" && xml.name () != "Hello") {
					if (arrAllLanguage.indexOf (xml.name ()) == -1 && xml.name () != "en") {
						trace()
						arrAllLanguage.push (String (xml.@code));
					}
				}
			}
			setLanguage (Capabilities.language);
			_isLanguageLoaded = true;
		}
		
		protected function languageChanged () : void {
		
		}
		
		public function languageError (e : Event = null) : void {
			errorMessage ("Language error, load the embed file");
			loadLocalLanguage ();
			_languageLoader = null;
		}
		
		public function setLanguage (langCode : String , type : String = "code" , def : String = "en") : void {
			trace ("MyMain: setLanguage" , langCode , type , def);
			
			curlanguage = langCode;
			
			_languageXML.ignoreWhitespace = true;
			_languageXML.ignoreComments = true;
			
			// Language by default
			var langDef : XMLList;
			if (type == "code") {
				langDef = _languageXML.lang.(@code == def).children ();
			} else if (type == "representation") {
				def = "en_EN";
				langDef = _languageXML.lang.(@representation == def).children ();
			}
			
			var xml : XML;
			for each (xml in langDef) {
				Localization[xml.@k] = xml;
			}
			
			var lang : XMLList;
			if (type == "code") {
				lang = _languageXML.lang.(@code == langCode).children ();
			} else if (type == "representation") {
				for each (xml in _languageXML.children ()) {
					if (xml.@representation == langCode) {
						lang = xml.children ();
					}
				}
			}
			
			for each (xml in lang) {
				Localization[xml.@k] = xml;
			}
			
			if (!lang || lang.length () == 0) {
				trace ("No language found");
				curlanguage = def;
			}
			languageChanged ();
		}
		
		protected function loadLocalLanguage () : void {
			trace ("MyMain: loadLocalLanguage");
			loadLanguageXML (_embedXML);
		}
		
		protected function initLanguageSelection (mc : MovieClip) : void {
		}
}