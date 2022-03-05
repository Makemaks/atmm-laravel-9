<link rel="stylesheet" type="text/css" href="css/landing.css">
<script src="https://nv681.infusionsoft.app/app/webTracking/getTrackingCode?b=1.70.0.112579" type="text/javascript">
</script>
<div class="text" id="webformErrors" name="errorContent">
</div>
<center>
<form accept-charset="UTF-8" action="https://nv681.infusionsoft.com/app/form/process/9e5f231e8c42ad8a74ef9c94ef6a804a" class="infusion-form" id="inf_form_9e5f231e8c42ad8a74ef9c94ef6a804a" method="POST" name="Web Form submitted" onsubmit="submitWebForm()">
<script type="text/javascript">

function submitWebForm() {
    var form = document.forms[0];
    var resolution = document.createElement('input');
    resolution.setAttribute('id', 'screenResolution');
    resolution.setAttribute('type', 'hidden');
    resolution.setAttribute('name', 'screenResolution');
    var resolutionString = screen.width + 'x' + screen.height;
    resolution.setAttribute('value', resolutionString);
    form.appendChild(resolution);
    var pluginString = '';
    if (window.ActiveXObject) {
    var activeXNames = {'AcroPDF.PDF':'Adobe Reader',
    'ShockwaveFlash.ShockwaveFlash':'Flash',
    'QuickTime.QuickTime':'Quick Time',
    'SWCtl':'Shockwave',
    'WMPLayer.OCX':'Windows Media Player',
    'AgControl.AgControl':'Silverlight'};
    var plugin = null;
    for (var activeKey in activeXNames) {
    try {
    plugin = null;
    plugin = new ActiveXObject(activeKey);
    } catch (e) {
    // do nothing, the plugin is not installed
    }
    pluginString += activeXNames[activeKey] + ',';
    }
    var realPlayerNames = ['rmockx.RealPlayer G2 Control',
    'rmocx.RealPlayer G2 Control.1',
    'RealPlayer.RealPlayer(tm) ActiveX Control (32-bit)',
    'RealVideo.RealVideo(tm) ActiveX Control (32-bit)',
    'RealPlayer'];
    for (var index = 0; index < realPlayerNames.length; index++) {
    try {
    plugin = new ActiveXObject(realPlayerNames[index]);
    } catch (e) {
    continue;
    }
    if (plugin) {
    break;
    }
    }
    if (plugin) {
    pluginString += 'RealPlayer,';
    }
    } else {
    for (var i = 0; i < navigator.plugins.length; i++) {
    pluginString += navigator.plugins[i].name + ',';
    }
    }
    pluginString = pluginString.substring(0, pluginString.lastIndexOf(','));
    var plugins = document.createElement('input');
    plugins.setAttribute('id', 'pluginList');
    plugins.setAttribute('type', 'hidden');
    plugins.setAttribute('name', 'pluginList');
    plugins.setAttribute('value', pluginString);
    form.appendChild(plugins);
    var java = navigator.javaEnabled();
    var javaEnabled = document.createElement('input');
    javaEnabled.setAttribute('id', 'javaEnabled');
    javaEnabled.setAttribute('type', 'hidden');
    javaEnabled.setAttribute('name', 'javaEnabled');
    javaEnabled.setAttribute('value', java);
    form.appendChild(javaEnabled);
}
</script>
<input name="inf_form_xid" type="hidden" value="9e5f231e8c42ad8a74ef9c94ef6a804a" />
<input name="inf_form_name" type="hidden" value="Web Form submitted" />
<input name="infusionsoft_version" type="hidden" value="1.70.0.112579" />
<div class="default beta-base beta-font-b col-md-12" id="mainContent">
    <div class="card">
        <table class="con-table">
            <tr>
                <td>
                    <img src="img/songwriterlogo.png" class="logo_image">
                </td> 
                <td></td>
            </tr>
            <tr>
                <td style="width: 40%; padding-top: 40px;">
                    <p class="heading-text">
                        Sometimes a song can teach us a truth the only way our hearts can hear it.
                    </p>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <div style="width: 70%; padding-top: 5px;">
                        <p class="sub-heading-text">
                            Sign up now to receive the latest and greatest on Michael Mcleans legacy project and become a VIP launch team member
                        </p>
                    </div>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <div style="padding-top: 15%;">
                        <input type="text" class="input" id="inf_field_FirstName" name="inf_field_FirstName" placeholder="First Name"><br><br>
                        <!-- <input type="email" class="input" placeholder="Last Name"><br><br> -->
                        <input type="email" id="inf_field_Email" name="inf_field_Email" class="input" placeholder="Email"><br><br>
                    </div>                        
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <button class="btn-signup" type="submit" value="Submit">Sign Up for Updates</button><br><br>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
</form>
</center>
<script type="text/javascript" src="https://nv681.infusionsoft.app/app/webTracking/getTrackingCode"></script>
<script type="text/javascript" src="https://nv681.infusionsoft.com/app/timezone/timezoneInputJs?xid=9e5f231e8c42ad8a74ef9c94ef6a804a"></script>