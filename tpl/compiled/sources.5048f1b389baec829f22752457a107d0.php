<?php if(!class_exists('raintpl')){exit;}?><p>Add, Remove, Enable, Disable, Sort and Configure data sources as appropriate for your situation.</p>
<p>Select which data source(s) to use for your lookups, and the order in which you want them used:</p>
<div id="CIDSourcesList">
    <script type="text/javascript" src="<?php echo $web_path;?>"></script>
    <script language="javascript">
        function moveup_source(id) {
            //This is suprisingly the BEST movement jquery method I have found! EVER
            var parent_id = $('#' + id).parent().parent().attr("id");
            var row = $('#' + id).parents("tr:first");
            $('#' + parent_id).fadeOut('slow', function() {
                row.insertBefore(row.prev());
                $('#' + parent_id).fadeIn('slow');
                source_order();
            })
        }
        function movedown_source(id) {
            //This is suprisingly the BEST movement jquery method I have found! EVER
            var parent_id = $('#' + id).parent().parent().attr("id");
            var row = $('#' + id).parents("tr:first");
            $('#' + parent_id).fadeOut('slow', function() {
                row.insertAfter(row.next());
                $('#' + parent_id).fadeIn('slow');
                source_order();
            })
        }
        
        //Fix graphics display and send content
        function source_order() {
            //Get order here and then re-do all of the gfx to make sense again to the gui
            var total = $('#sources tr').size();
            var source_json="[";
            $('#sources tr').each(function(index) {
                var id = $(this).attr("id");
                if(($(this).attr("id") != 'title_row') && $('#'+id+'_enabled').is(':checked')) {
                    
                    if(index == 1) {
                        $('#' + id + '_moveup').hide();
                        $('#' + id + '_movedown').show();
                        source_json = source_json + '"'+ id +'",';
                    } else {
                        var nextid = $(this).next().attr('id');
                        if($('#'+nextid+'_enabled').is(':checked')) {
                            $('#' + id + '_movedown').show();
                            source_json = source_json + '"'+ id +'",'
                        } else {
                            $('#' + id + '_movedown').hide();
                            source_json = source_json + '"'+ id +'"'
                        }
                        $('#' + id + '_moveup').show();
                    }
                }
            });
            source_json = source_json + "]";
            console.log(source_json);
            //Send Scheme now (over ajax)
        }
        
        function disable_source(id) {
            var parent_id = $('#' + id).parent().parent().attr("id");
            $('#' + parent_id + '_moveup').fadeOut('slow');
            $('#' + parent_id + '_movedown').fadeOut('slow');
            $('#sources tr').each(function(index) {
                var eid = $(this).attr("id");
                if(($(this).attr("id") != 'title_row') && !$('#'+eid+'_enabled').is(':checked') && parent_id != eid) {
                    $('#' + parent_id).insertBefore($(this));
                    source_order();
                    return false;
                }
            });
            
        }
        
        function enable_source(id) {
            var parent_id = $('#' + id).parent().parent().attr("id");
            $('#' + parent_id + '_moveup').fadeIn('slow');
            $('#' + parent_id + '_movedown').fadeIn('slow');
            $('#sources tr').each(function(index) {
                var eid = $(this).attr("id");
                if(($(this).attr("id") != 'title_row') && !$('#'+eid+'_enabled').is(':checked') && parent_id != eid) {
                    alert('this isnt working yet');
                    return false;
                }
            });
        }
        
    </script>
    <script language="javascript">
        /**
        function change(source, type) {
            if(type == 'enable') {
                var row = $("#"+source).html();
                var frow = '';
                var found = false;
                $.ajaxSetup({ cache: false });
                $.getJSON("config.php?quietmode=1&handler=file&module=superfecta&file=ajax.html.php&type=add&scheme=<?php echo $scheme;?>&source="+source, function(json) {
                    if(json.success) {
                        $("#sources :checked").each(function(){
                            if(($(this).val() == 'disabled') && (!found) ) {
                                frow = $(this).attr('id').replace("_disabled","");
                                found = true;
                            }
                        });
                        $('#'+source).remove();
                        $('#'+frow).before('<tr id="'+source+'" class="enabled">' + row + '<tr>');
                        $('input[name="'+source+'"]').val(['enabled']);
                    } else {
                        $('input[name="'+source+'"]').val(['disabled']);
                        $('#message').html('Error');
                    }
                });
            } else if(type == 'disable') {
                var row = $("#"+source).html();
                $.ajaxSetup({ cache: false });
                $.getJSON("config.php?quietmode=1&handler=file&module=superfecta&file=ajax.html.php&type=remove&scheme=<?php echo $scheme;?>&source="+source, function(json) {
                    if(json.success) {
                        $('#'+source).remove();
                        $('#sources tr:last').after('<tr id="'+source+'" class="disabled">' + row + '<tr>');
                        $('input[name="'+source+'"]').val(['enabled']);
                    } else {
                        $('input[name="'+source+'"]').val(['enabled']);
                        $('#message').html('Error');
                    }
                });
            }
        }
    
        function options(source) {
            $('#options').fadeOut('slow', function() {
                $.ajaxSetup({ cache: false });
                $.getJSON("config.php?quietmode=1&handler=file&module=superfecta&file=ajax.html.php&type=options&scheme=<?php echo $scheme;?>&source="+source, function(json) {
                    if(json.success && json.show) {
                        $('#options').fadeIn('slow').html(json.data);
                        $('#form_options_'+source).ajaxForm(function() { 
                            alert("Saved!"); 
                        }); 
                    }
                });
            });
        }
    
        function move(source,action) {
            $.ajaxSetup({ cache: false });
            $.getJSON("config.php?quietmode=1&handler=file&module=superfecta&file=ajax.html.php&type=move&a="+action+"&scheme=<?php echo $scheme;?>&source="+source, function(json) {
                if(json.success) {
                    $('#'+source).remove();
                    //$('#'+frow).before('<tr id="'+source+'" class="enabled">' + row + '<tr>');
                }
            });
        }
        **/
    </script>
    <input type="hidden" name="src_up" value="">
    <input type="hidden" name="src_down" value="">
    <input type="hidden" name="selected_source" value="">
    <input type="hidden" name="update_file" value="">
    <input type="hidden" name="delete_file" value="">
    <input type="hidden" name="revert_file" value="">
    <span id="message" style="color:red;font-weight:bolder"><?php echo $update_site_message;?></span></br>
    <font size=2><input type="checkbox" name="check_updates" value="yes" <?php echo $check_updates_check;?>">&nbsp;Check for Data Source File updates online.<br></font>

    <table>
        <tr>
            <td>
                <table border="0" id="sources" cellspacing="0" cellpadding="2">
                    <tr id="title_row">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><strong>Data Source Name</strong></td>
                        <td align="center"><strong>Disabled</strong></td>
                        <td align="center"><strong>Enabled</strong></td>
                    </tr>
                    <?php $counter1=-1; if( isset($sources) && is_array($sources) && sizeof($sources) ) foreach( $sources as $key1 => $value1 ){ $counter1++; ?>

                    <tr id="<?php echo $value1["source_name"];?>" class="<?php echo $value1["status"];?>">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><img id="<?php echo $value1["source_name"];?>_movedown" onclick="movedown_source(this.id);" src="images/scrolldown.gif" <?php if( $value1["showdown"] === FALSE ){ ?>hidden<?php } ?>/></td>
                        <td><img id="<?php echo $value1["source_name"];?>_moveup" onclick="moveup_source(this.id);" src="images/scrollup.gif" <?php if( $value1["showup"] === FALSE ){ ?>hidden<?php } ?>/></td>
                        <td><a onclick="options('<?php echo $value1["source_name"];?>')"><?php echo $value1["pretty_source_name"];?></a></td>
                        <td align="center"><input type="radio" id="<?php echo $value1["source_name"];?>_disabled" name="<?php echo $value1["source_name"];?>" value="disabled" onclick="disable_source(this.id)" <?php if( $value1["enabled"] === FALSE ){ ?>checked<?php } ?>/></td>
                        <td align="center"><input type="radio" id="<?php echo $value1["source_name"];?>_enabled" name="<?php echo $value1["source_name"];?>" value="enabled" onclick="enable_source(this.id)" <?php if( $value1["enabled"] === TRUE ){ ?>checked<?php } ?>/></td>
                    </tr>
                    <?php } ?>

                </table>
            </td>
            <td valign="top">
                <div id="options" style="background: #C0C0C0"></div>
            </td>
        </tr>
    </table>

</div>
<br><br>
<table border="0">
    <tr>
        <td valign="top">
            <form method="POST" action="" name="Superfecta">
                <input type="hidden" name="scheme_name_orig" value="<?php echo $scheme_name;?>">
                <table border="0" id="table1" cellspacing="1">
                    <tr>
                        <td><a href="javascript:return(false);" class="info"><strong>Scheme Name:</strong><span>Duplicate Scheme names not allowed.</span></a></td>
                        <td><input type="text" name="scheme_name" size="23" maxlength="20" value="<?php echo $scheme_name;?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><font face="Arial"><br><u>General Options</font></u></td>
                    </tr>


                    <tr>
                        <td valign="top"><a href="javascript:return(false);" class="info">DID Rules<span>Define the expected DID Number if your trunk passes DID on incoming calls. <br><br>Leave this blank to match calls with any or no DID info.<br><br>This rule trys both absolute and pattern matching (eg "_2[345]X", to match a range of numbers). (The "_" underscore is optional.)</span></a>:</td>
                        <td>
                            <textarea tabindex="1" cols="20" rows="5" name="DID"><?php echo $did;?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <a href="javascript:return(false);" class="info">CID Rules<span>Incoming calls with CID matching the patterns specified here will use this CID Scheme. If this is left blank, this scheme will be used for any CID. It can be used to add or remove prefixes.<br>
                                    <strong>Many sources require a specific number of digits in the phone number. It is recommended that you use the patterns to remove excess country code data from incoming CID to increase the effectiveness of this module.</strong><br>
                                    Note that a pattern without a + or | (to add or remove a prefix) will not make any changes but will create a match. Only the first matched pattern will be executed and the remaining rules will not be acted on.<br /><br /><b>Rules:</b><br />
                                    <strong>X</strong>&nbsp;&nbsp;&nbsp; matches any digit from 0-9<br />
                                    <strong>Z</strong>&nbsp;&nbsp;&nbsp; matches any digit from 1-9<br />
                                    <strong>N</strong>&nbsp;&nbsp;&nbsp; matches any digit from 2-9<br />
                                    <strong>[1237-9]</strong>&nbsp;   matches any digit or letter in the brackets (in this example, 1,2,3,7,8,9)<br />
                                    <strong>.</strong>&nbsp;&nbsp;&nbsp; wildcard, matches one or more characters (not allowed before a | or +)<br />
                                    <strong>|</strong>&nbsp;&nbsp;&nbsp; removes a dialing prefix from the number (for example, 613|NXXXXXX would match when some one dialed "6135551234" but would only pass "5551234" to the Superfecta look up.)<br><strong>+</strong>&nbsp;&nbsp;&nbsp; adds a dialing prefix to the number (for example, 1613+NXXXXXX would match when someone dialed "5551234" and would pass "16135551234" to the Superfecta look up.)<br /><br />
                                    You can also use both + and |, for example: 01+0|1ZXXXXXXXXX would match "016065551234" and dial it as "0116065551234" Note that the order does not matter, eg. 0|01+1ZXXXXXXXXX does the same thing.</span></a>:
                        </td>
                        <td valign="top">
                            <textarea tabindex="2" id="dialrules" cols="20" rows="5" name="CID_rules"><?php echo $cid_rules;?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">Lookup Timeout<span>Specify a timeout in seconds for each source. If the source fails to return a result within the alloted time, the script will move on.</span></a></td>
                        <td><input type="text" name="Curl_Timeout" size="4" maxlength="5" value="<?php echo $curl_timeout;?>"></td>
                    </tr>
                    <tr>
                        <td>
                            <a href="javascript:return(false);" class="info">Superfecta Processor
                                <span>These are the types of Superfecta Processors:<br />
                                    <?php $counter1=-1; if( isset($processors_list) && is_array($processors_list) && sizeof($processors_list) ) foreach( $processors_list as $key1 => $value1 ){ $counter1++; ?>

                                    <strong><?php echo $value1["name"];?>:</strong> <?php echo $value1["description"];?><br />
                                    <?php } ?>

                                </span></a>
                        </td>
                        <td>
                            <select name="processor">
                                <?php $counter1=-1; if( isset($processors_list) && is_array($processors_list) && sizeof($processors_list) ) foreach( $processors_list as $key1 => $value1 ){ $counter1++; ?>

                                <option value='<?php echo $value1["filename"];?>' <?php if( $value1["selected"] === TRUE ){ ?>selected<?php } ?>><?php echo $value1["name"];?></option>
                                <?php } ?>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">Multifecta Timeout<span>Specify a timeout in seconds defining how long multifecta will obey the source priority. After this timeout, the first source to respond with a CNAM will be taken, until "Lookup Timeout" is reached.</span></a></td>
                        <td><input type="text" name="multifecta_timeout" size="4" maxlength="5" value="<?php echo $multifecta_timeout;?>"></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">CID Prefix URL<span>If you wish to prefix information on the caller id you can specify a url here where that prefix can be retrieved.<br>The data will not be parsed in any way, and will be truncated to the first 10 characters.<br>Example URL: http://www.example.com/GetCID.php?phone_number=[thenumber]<br>[thenumber] will be replaced with the full 10 digit phone number when the URL is called.</span></a></td>
                        <td><input type="text" name="Prefix_URL" size="23" maxlength="255" value="<?php echo $prefix_url;?>"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">SPAM Text<span>This text will be prepended to Caller ID information to help you identify calls as SPAM calls.</span></a></td>
                        <td><input type="text" name="SPAM_Text" size="23" maxlength="20" value="<?php echo $spam_text;?>"></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">SPAM Text Substituted<span>When enabled, the text entered in "SPAM Text" (above) will replace the CID completely rather than pre-pending the CID value.</span></a></td>
                        <td>
                            <input type="checkbox" name="SPAM_Text_Substitute" value="Y" <?php if( $spam_text_substitute === TRUE ){ ?>checked<?php } ?>>
                        </td>
                    </tr>
                    <tr>
                        <td><a href="javascript:return(false);" class="info">Enable SPAM Interception<span>When enabled, Spam calls can be diverted or terminated.</span></a></td>
                        <td>
                            <input type="checkbox" onclick="toggleInterceptor()" name="enable_interceptor" value="Y" <?php if( $spam_int === TRUE ){ ?>checked<?php } ?>>
                        </td>
                    </tr>
                </table>
                <table id="InterceptorVector" border="0">
                    <tr>
                        <td><a href="javascript:return(false);" class="info">SPAM Send Threshold<span>This is the threshold to send the call to the specified destination below</span></a></td>
                        <td><input type="text" name="SPAM_threshold" size="4" maxlength="2" value="<?php echo $spam_threshold;?>"></td>
                    </tr>
                    <tr class="incerceptorCell">
                        <td colspan="2">Send Spam Call To:</td>
                    </tr>
                    <tr class="incerceptorCell">
                        <td colspan="2"><?php echo $interceptor_select;?></td>
                    </tr>
                </table>
                <p><a target="_blank" href="modules/superfecta/disclaimer.html">(License Terms)&nbsp; </a><input type="submit" value="Agree and Save" name="Save"></p>
                <p style="font-size:12px;">(* By clicking on either the &quot;Agree and Save&quot;<br>button, or the &quot;Debug&quot; button on this form<br>you are agreeing to the Caller ID Superfecta<br>Licensing Terms.)</p>
            </form>
        </td>				
        <td valign="top">
            <form name="debug_form" action="javascript:Ht_debug(document.forms.debug_form.thenumber.value,<?php echo $did_test_script;?>document.forms.debug_form.Allscheme.checked,document.forms.debug_form.debug.value);">
                <p>Test a phone number against the selected sources.<br>
                    <?php echo $did_test_html;?>

                    <a href="javascript:return(false);" class="info">Phone Number:<span>Phone number to test this scheme against.</span></a> <input type="text" size="15" maxlength="20" name="thenumber"> <input type="submit" value="Debug"><br>
                    <font size=2><input type="checkbox" name="Allscheme" value="All">
                    <a href="javascript:return(false);" class="info">Test all CID schemes<span>When enabled, the debug function will test the number entered against all of the configured CID schemes.<br>When disabled, debug only checks up to the first scheme that provides positive results.</span></a> <br/>Debug Level:<select name="debug" id="debug_level"><option value="0">NONE</option><option value="1" selected>INFO</option><option value="2">WARN</option><option value="3">ALL</option></select></font></p>
            </form>
            <div id="debug" style="background-color: #E0E0E0; width:100%"></div>
        </td>
    </tr>
</table>
<script language="javascript">
    <!--
    var isWorking = false;
    var divname = '';
    var http = getHTTPObject();

    $(".cats").change(function () {
        var str =new Array();
        var i = 0;
        $(".cats option:selected").each(function () {
            str[i] = $(this).text();
            i ++; 
        });
        Ht_Generate_List('','&lt;?php echo $scheme;?&gt;',str);
    })
    .change();
    
    function array2json(arr) {
        var parts = [];
        var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

        for(var key in arr) {
            var value = arr[key];
            if(typeof value == "object") { //Custom handling for arrays
                if(is_list) parts.push(array2json(value)); /* :RECURSION: */
                else parts[key] = array2json(value); /* :RECURSION: */
            } else {
                var str = "";
                if(!is_list) str = '"' + key + '":';

                //Custom handling for multiple data types
                if(typeof value == "number") str += value; //Numbers
                else if(value === false) str += 'false'; //The booleans
                else if(value === true) str += 'true';
                else str += '"' + value + '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Functions?)

                parts.push(str);
            }
        }
        var json = parts.join(",");
    
        if(is_list) return '[' + json + ']';//Return numerical JSON
        return '{' + json + '}';//Return associative JSON
    }

    function is_null(input){
        return input==null;
    }

    function getHTTPObject()
    {
        var xmlhttp;
        //do not take out this section of code that appears to be commented out...if you do the guns stop working.
        /*@cc_on
        @if (@_jscript_version >= 5)
        try
        {
                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (e)
        {
                try
                {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch (E)
                {
                        xmlhttp = false;
                }
        }
        @else
        {
                xmlhttp = false;
        }
        @end @*/

        if(!xmlhttp && typeof XMLHttpRequest != 'undefined')
        {
            try
            {
                xmlhttp = new XMLHttpRequest();
            }
            catch (e)
            {
                xmlhttp = false;
            }
        }
        return xmlhttp;
    }

    function Ht_Response()
    {
        if (http.readyState == 4)
        {
            document.getElementById(divname).innerHTML = http.responseText;
            isWorking = false;
            reset_infoboxes();
        }
    }

    function Ht_debug(thenumber,testdid,checkall,debuglevel)
    {
        thenumber = thenumber || "";
        testdid = testdid || "";
        checkall = checkall || false;
        var poststr = "debug=" + debuglevel + "&thenumber=" + thenumber + "&testdid=" + testdid;
        if(!checkall)
        {
            poststr = poststr + "&scheme=&lt;?php print $scheme ?&gt;";
        }
        else
        {
            poststr = poststr + "&scheme=base_ALL_ALL";
        }

        if(!isWorking)
        {
            isWorking = true;
            divname = 'debug';
            document.getElementById(divname).innerHTML = "<img src='modules/superfecta/loading.gif' style='margin: 20px auto 20px 150px;'>";
            http.open("POST", "modules/superfecta/includes/callerid.php", true);
            http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            http.setRequestHeader("Content-length", poststr.length);
            http.setRequestHeader("Connection", "close");
            http.onreadystatechange = Ht_Response;
            http.send(poststr);
        }
        else
        {
            setTimeout("Ht_debug('" + thenumber + "','" + testdid + "'," + checkall + ")",100);
        }
    }

    function reset_infoboxes(){
        body_loaded();
        // test for a function that seems to only be in freepbx 2.8+
        if(typeof window.tabberAutomaticOnLoad == 'function') {
            $("a.info").hover(function () {
                var pos = $(this).offset();
                var left = (200 - pos.left) + "px";
                $(this).find("span").css("left", left).stop(true, true).delay(500).animate({
                    opacity: "show"
                }, 750);
            }, function () {
                $(this).find("span").stop(true, true).animate({
                    opacity: "hide"
                }, "fast");
            });
        }
    }

    function toggleInterceptor() {
	
        var row = document.getElementById("InterceptorVector");
        if(document.Superfecta.enable_interceptor.checked && row)
        {
            row.style.display = '';
        }
        else if(row)
        {
            row.style.display = 'none';
        }
    }

    function decision(message, url)
    {
        if(confirm(message)) location.href = url;
    }

    //-->
</script>