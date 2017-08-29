using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using System.Xml;
using UnityEngine.UI;

public class Localization : MonoBehaviour
{
    static public bool DebugMode = false;
    public string Key = "";
    public Style ForceStyle = Style.None;
    public static string DefaultLanguage = "English";
    public static string CurrentLanguage = "English";

    bool alreadyLoaded = false;

    public enum Style
    {
        None,
        Uppercase,
        Lowercase
    }

    static private Dictionary<string, Dictionary<string, string>> DataBase = new Dictionary<string, Dictionary<string, string>> ();

    void OnEnable ()
    {
        if ( alreadyLoaded )
            return;
        if ( hasTextField && Key != "" ) {

            if ( KeyValue ( Key, text ) == Key ) {
                Debug.LogWarning ( "Localization: No key <" + Key + "> defined or found." );
            } else {
                if ( text == "" ) {
                    text = Key;
                }
            }
        } else if ( hasTextField && text != "" ) {
            string t = KeyValue ( text );
            if ( t == text ) {
                Debug.LogWarning ( "Localization: No key <" + text + "> defined or found." );
            } else {
                text = t;
            }
        } else {
            //Debug.LogWarning ( "Localization: No key or text defined or found in <" + name + ">" );
        }
        alreadyLoaded = true;
    }

    protected string text {
        set {

            if ( ForceStyle == Style.Uppercase ) {
                value = value.ToUpper ();
            } else if ( ForceStyle == Style.Uppercase ) {
                value = value.ToLower ();
            }
            if ( GetComponent<TextMesh> () )
                GetComponent<TextMesh> ().text = value;
            if ( GetComponent<Text> () )
                GetComponent<Text> ().text = value;
        }
        get {
            if ( GetComponent<TextMesh> () )
                return GetComponent<TextMesh> ().text;
            if ( GetComponent<Text> () )
                return GetComponent<Text> ().text;
            return string.Empty;
        }
    }

    protected bool hasTextField {
        get {
            if ( GetComponent<TextMesh> () )
                return true;
            else if ( GetComponent<Text> () )
                return true;
            return false;
        }
    }

    static public string KeyValue (string key)
    {
        if ( DataBase.ContainsKey ( CurrentLanguage ) && DataBase [ CurrentLanguage ].ContainsKey ( key ) ) {
            Dictionary<string, string> dict = DataBase [ CurrentLanguage ];
            return dict [ key ].Replace ( "<br/>", "\n" ).Replace ( "<br />", "\n" ).Replace ( "<BR/>", "\n" ).Replace ( "<BR />", "\n" );
        } else if ( DataBase.ContainsKey ( DefaultLanguage ) && DataBase [ DefaultLanguage ].ContainsKey ( key ) ) {
            Dictionary<string, string> dict = DataBase [ DefaultLanguage ];
            return dict [ key ].Replace ( "<br/>", "\n" ).Replace ( "<br />", "\n" ).Replace ( "<BR/>", "\n" ).Replace ( "<BR />", "\n" );
        } else {
            Debug.LogWarning ( "Localization: No key <" + key + "> locale: " + DefaultLanguage + " found in the XML" );
            return key;
        }
    }

    static public string KeyValue (string key, string defaultText)
    {

        string v = KeyValue ( key );
        if ( v == key && defaultText != "" ) {
            return defaultText;
        } else {
            return v;
        }
    }

    static public void AddKeyValue (string lang, string key, string value)
    {
        DataBase [ lang ].Add ( key, value );
    }

    static public void AddXML (string path)
    {
        AddXML ( path, Application.systemLanguage.ToString () );
    }

    static public void AddXML (string path, string curLanguage)
    {
        Debug.Log ( "Localization: AddXML path: " + path + " curLanguage: " + curLanguage );
        CurrentLanguage = curLanguage;
        TextAsset textAsset = ( TextAsset ) Resources.Load ( path );
        XmlDocument xml = new XmlDocument ();
        xml.LoadXml ( textAsset.text );

        XmlNode nodes = xml.SelectSingleNode ( "//game" );
        foreach ( XmlNode node in nodes.ChildNodes ) {
            string lang = node.Attributes [ "english" ].Value;
            if ( !DataBase.ContainsKey ( lang ) ) {
                DataBase [ lang ] = new Dictionary<string, string> ();
            }
            //XmlNode newNodes = xml.SelectSingleNode ( "//game/lang[@english]" + node.Name );
            foreach ( XmlNode n in node.ChildNodes ) {

                string key = n.Attributes [ "k" ].Value;
                string value = n.FirstChild.Value;
                if ( DataBase [ lang ].ContainsKey ( key ) )
                    DataBase [ lang ].Remove ( key );

                if ( DebugMode )
                    Debug.Log ( "Lang: " + lang + "\t" + key + ": " + value );
                DataBase [ lang ].Add ( key, value.Replace ( "\\n", "\n" ) );
            }
        }
    }

    static public void AddTSV (string path)
    {
        AddTSV ( path, Application.systemLanguage.ToString () );
    }

    static public void AddTSV (string path, string curLanguage)
    {
        Debug.Log ( "Localization: AddTSV path: " + path + " curLanguage: " + curLanguage );
        CurrentLanguage = curLanguage;
        TextAsset textAsset = ( TextAsset ) Resources.Load ( path );
        if ( DebugMode )
            Debug.Log ( textAsset );
        string [] lines = textAsset.text.Split ( "\n" [ 0 ] );
        string [] languages = lines [ 0 ].Split ( "\t" [ 0 ] );
        foreach ( string lang in languages ) {
            if ( lang != string.Empty ) {
                DataBase.Add ( lang, new Dictionary<string, string> () );
            }
        }
        int i = 0;

        foreach ( string l in lines ) {
            i++;
            if ( i >= 3 ) {
                string [] arrLine = l.Split ( "\t" [ 0 ] );
                string key = arrLine [ 0 ];
                for ( int c = 1; c < arrLine.Length; c++ ) {
                    if ( DebugMode ) {
                        Debug.Log ( "language: " + languages [ c ] + " key: " + key + " value:" + arrLine [ c ] );
                    }
                    if ( key != "" && arrLine [ c ] != "" )
                        DataBase [ languages [ c ] ].Add ( key, arrLine [ c ] );
                }
            }
        }
    }

    static public void AddCSV (string path)
    {
        AddCSV ( path, Application.systemLanguage.ToString () );
    }

    static public void AddCSV (string path, string curLanguage)
    {
        Debug.Log ( "Localization: AddCSV path: " + path + " curLanguage: " + curLanguage );
        CurrentLanguage = curLanguage;
        TextAsset textAsset = ( TextAsset ) Resources.Load ( path );
        string [] lines = textAsset.text.Split ( "\n" [ 0 ] );
        string [] languages = lines [ 0 ].Split ( "," [ 0 ] );
        foreach ( string lang in languages ) {
            if ( lang != string.Empty ) {
                DataBase.Add ( lang, new Dictionary<string, string> () );
            }
        }
        int i = 0;

        foreach ( string l in lines ) {
            i++;
            if ( i >= 3 ) {

                string [] arrLine = l.Split ( "," [ 0 ] );
                string key = arrLine [ 0 ];

                for ( int c = 1; c < arrLine.Length; c++ ) {
                    if ( arrLine [ c ] != string.Empty ) {
                        if ( DebugMode ) {
                            Debug.Log ( languages [ c ] + " " + key + " " + arrLine [ c ] );
                        }
                        DataBase [ languages [ c ] ].Add ( key, arrLine [ c ] );
                    }
                }
            }
        }
    }


    static public void DebugCurrentDatas ()
    {
        Debug.Log ( "Localization: DebugCurrentDatas " + CurrentLanguage );
        if ( !DataBase.ContainsKey ( CurrentLanguage ) ) {
            Debug.LogError ( "Localization: No language <" + CurrentLanguage + "> added in data base " );
            return;
        }
        Dictionary<string, string> dict = DataBase [ CurrentLanguage ];
        foreach ( KeyValuePair<string, string> entry in dict ) {
            Debug.Log ( entry.Key + " " + entry.Value );
        }
    }

}