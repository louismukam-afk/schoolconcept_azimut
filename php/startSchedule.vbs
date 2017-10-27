set shell = wscript.createobject("wscript.shell")

Set fso = WScript.CreateObject("Scripting.Filesystemobject")
Set fil=fso.GetFile(WScript.ScriptFullName)
Set fold=fil.ParentFolder
Set fold2=fold.ParentFolder
return = shell.Run(""""&fold & "\php.exe"" """ & fold2 &"\artisan"" schedule:run",0,false)
