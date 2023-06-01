<HTML>
    <HEAD>
        <TITLE>
            Signature image hexadecimal string conversion
        </TITLE>

    <table border=1 cellpadding="0" height="100" width="400">
        <tr>
            <td height="1" width="400"> <OBJECT classid=clsid:69A40DA3-4D42-11D0-86B0-0000C025864A height=100
                                                id=SigPlus1 name=SigPlus1
                                                style="HEIGHT: 100px; LEFT: 0px; TOP: 0px; WIDTH: 400px" width=400
                                                VIEWASTEXT>
                    <PARAM NAME="_Version" VALUE="131095">
                    <PARAM NAME="_ExtentX" VALUE="4842">
                    <PARAM NAME="_ExtentY" VALUE="1323">
                    <PARAM NAME="_StockProps" VALUE="0">
                </OBJECT>
            </td>
        </tr></table>

    <SCRIPT LANGUAGE=javascript>


        function onClear() {
            SigPlus1.ClearTablet();
        }


        function onSign() {
            SigPlus1.TabletState = 1; //Capture signature
        }


        function onImgString() {
            if (SigPlus1.NumberOfTabletPoints() > 0)
            {
                SigPlus1.TabletState = 0; //turn off pad
                SigPlus1.ImageFileFormat = 0; //0=bmp, 4=jpg, 6=tif
                SigPlus1.ImageXSize = 500; //width of resuting image in pixels
                SigPlus1.ImageYSize = 100; //height of resulting image in pixels
                SigPlus1.ImagePenWidth = 8; //thickness of ink in pixels
                SigPlus1.JustifyX = 10; //buffer on left and right to not lose pixels
                SigPlus1.JustifyY = 10; //buffer on top and bottom to not lose pixels
                SigPlus1.JustifyMode = 5; //Center and blow up signature as large as possible
                var bmpString = '';
                SigPlus1.BitMapBufferWrite();
                var bmpSize = SigPlus1.BitMapBufferSize();
                for (var a = 0; a < bmpSize; a++)
                {
                    var byte = SigPlus1.BitMapBufferByte(a).toString(16);
                    if (byte.length === 1)
                    {
                        bmpString += '0';
                    }
                    bmpString += byte;
                }
                SigPlus1.BitMapBufferClose();
                //alert("Signature byte array string conversion: " + bmpString);
                document.sigForm.SigField.value = bmpString;
                //alert("Check string");
                document.sigForm.submit();
            } else
            {
                alert("Please sign before continuing");
            }


        }


    </SCRIPT>

</HEAD>
<BODY>
    <FORM action="createimg.php" id="sigForm" method="post" name="sigForm">
        <P>
            <INPUT id=SignBtn name=SignBtn type=button value=Sign language ="javascript" onclick="onSign()">&nbsp;&nbsp;
            <INPUT id=submit1 name=ImgStringBtn type=button value="Image String" language ="javascript" onclick="onImgString()">
            <INPUT id=button1 name=ClearBtn type=button value=Clear language ="javascript" onclick="onClear()">&nbsp;&nbsp;
            <INPUT type=hidden id=SigField name=SigField>
        </P>
    </FORM>
</BODY>
</HTML>