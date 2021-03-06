<div class="wordfenceModeElem" id="wordfenceMode_passwd"></div>
<div class="wrap" id="paidWrap">
	<?php require('menuHeader.php'); ?>
	<?php $pageTitle = "Audit the Strength of your Passwords";
	$helpLink = "http://docs.wordfence.com/en/Wordfence_Password_Auditing";
	$helpLabel = "Learn more about Password Auditing";
	include('pageTitle.php'); ?>
	<?php if (!wfConfig::get('isPaid')) { ?>
		<div class="wordfenceRightRail">
			<ul>
				<li><a href="https://www.wordfence.com/gnl1rightRailGetPremium/wordfence-signup/" target="_blank"><img src="<?php echo wfUtils::getBaseURL() . 'images/rr_premium.png'; ?>" alt="Upgrade your protection - Get Wordfence Premium"></a></li>
				<li><a href="https://www.wordfence.com/gnl1rightRailSiteCleaning/wordfence-site-cleanings/" target="_blank"><img src="<?php echo wfUtils::getBaseURL() . 'images/rr_sitecleaning.jpg'; ?>" alt="Have you been hacked? Get help from Wordfence"></a></li> 
				<li>
					<p class="center"><strong>Would you like to remove these ads?</strong><br><a href="https://www.wordfence.com/gnl1rightRailBottomUpgrade/wordfence-signup/" target="_blank">Get Premium</a></p>
				</li>
			</ul>
		</div>
	<?php } ?>
	<?php if (!wfConfig::get('isPaid')) { ?>
		<div class="wf-premium-callout" style="margin: 20px 0 20px 20px; width: 700px;">
			<h3>Password Auditing is only available to Premium Members</h3>
			<p>Wordfence Password Auditing uses our high performance password auditing cluster to test the strength of your admin and user passwords. We securely simulate a high-performance password cracking attack on your password database and will alert you to weak passwords. We then provide a way to change weak passwords or alert members that they need to improve their password strength.</p>

			<p>Upgrade today:</p>
			<ul>
				<li>Receive real-time Firewall and Scan engine rule updates for protection as threats emerge</li>
				<li>Other advanced features like IP reputation monitoring, an advanced comment spam filter, advanced scanning options, cell phone sign-in and country blocking give you the best protection available</li>
				<li>Access to Premium Support</li>
				<li>Discounts of up to 90% available for multiyear and multi-license purchases</li>
			</ul>
			<p class="center"><a class="button button-primary" href="https://www.wordfence.com/gnl1pwAuditUp1/wordfence-signup/" target="_blank">Get Premium</a></p>
		</div>
	<?php } ?>

	<div class="wordfenceWrap" style="margin: 20px 20px 20px 30px;">
		<h2>Start a Password Audit</h2>
		<table class="wfConfigForm" width="800px">
			<tr>
				<td colspan="2">Audit your site passwords by having
					us securely simulate a password cracking attempt using our high performance servers.
					Your report will appear here and you can easily alert your users to a weak password or change their passwords and email them the change.
				</td>
			</tr>
			<tr>
				<th colspan="2">Select the kind of audit you would like to do:</th>
			</tr>
			<tr>
				<th colspan="2">
					<select id="auditType">
						<option value="admin">Audit administrator level accounts (extensive audit against a large dictionary of approx. 260 Million passwords)</option>
						<option value="user">Audit user level accounts (less extensive against a dictionary of approximately 50,000 passwords)</option>
						<option value="both">Audit all WordPress accounts</option>
					</select>
				</th>
			</tr>
			<tr>
				<th>Results will appear on this page. We will email you when they're ready. Enter the email address we should email:</th>
				<td><input type="text" id="emailAddr" size="50" maxlength="255" value="<?php wfConfig::f('alertEmails') ?>"/></td>
			</tr>
			<tr>
				<td colspan="2"><input type="button" name="but4" class="button-primary" value="Start Password Audit"
				                       onclick="WFAD.startPasswdAudit(jQuery('#auditType').val(), jQuery('#emailAddr').val());"/>
				</td>
			</tr>

		</table>
		<h2 style="margin-top: 20px;">Audit Status:</h2>

		<div id="wfAuditJobs">
		</div>
		<h2 style="margin-top: 20px;">Password Audit Results:</h2>

		<div id="wfAuditResults">
		</div>
	</div>

</div>
<script type="text/x-jquery-template" id="wfAuditResultsTable">
<div style="margin: 0 0 20px 0;">
	<select id="wfPasswdFixAction">
		<option value="email">Action: Email selected users and ask them to change their weak password.</option>
		<option value="fix">Action: Change weak passwords to a strong password and email users the new password.</option>
	</select><input type="button" value="Fix Weak Passwords" onclick="WFAD.doFixWeakPasswords(); return false;" class="button-primary"/>
</div>
<table class="wf-table">
	<thead>
		<th style="text-align: center">
			<input type="checkbox" id="wfSelectAll" onclick="jQuery('.wfUserCheck').attr('checked', this.checked);" />
		</th>
		<th>User Level</th>
		<th>Username</th>
		<th>Full Name</th>
		<th>Email</th>
		<th>Password</th>
		<th>Crack Time</th>
		<th>Crack Difficulty</th>
	</thead>
	<tbody class="wf-pw-audit-tbody"></tbody>
</table>
</script>

<script type="text/x-jquery-template" id="wfAuditResultsRow">
<tr>
	<td style="text-align: center;">
		<input type="checkbox" class="wfUserCheck" value="${wpUserID}"/>
	</td>
	<td>{{if wpIsAdmin == '1'}}<span style="color: #F00;">Admin</span>{{else}}User{{/if}}</td>
	<td>${username}</td>
	<td>${firstName} ${lastName}</td>
	<td>${email}</td>
	<td>${starredPassword}</td>
	<td>${crackTime}</td>
	<td>${crackDifficulty}</td>
</tr>
</script>

<script type="text/x-jquery-template" id="wfAuditJobsTable">
<table class="wf-table">
	<thead>
		<th>Audit Type</th>
		<th>Admin Accounts</th>
		<th>User Accounts</th>
		<th>Run Time</th>
		<th>Email results to</th>
		<th>Weak Passwords Found</th>
		<th colspan="2">Status</th>
	</thead>
	<tbody class="wf-pw-audit-tbody"></tbody>
</table>
</script>
<script type="text/x-jquery-template" id="wfAuditJobsInProg">
<tr>
	<td>
		{{if auditType == 'admin'}}
		Admin Accounts
		{{else auditType == 'user'}}
		User Accounts
		{{else auditType == 'both'}}
		All WordPress Accounts
		{{/if}}
	</td>
	<td>${totalAdmins}</td>
	<td>${totalUsers}</td>
	<td>${WFAD.makeTimeAgo(timeTaken)}</td>
	<td>${email}</td>
	<td>${weakFound}</td>
	{{if jobStatus == 'done'}}
	<td colspan="2">
		<span style="color: #FFC200;">Complete</span>
	</td>
	{{else jobStatus == 'killed'}}
    <td colspan="2">
		<span style="color: #A00;">Stopped</span>
	</td>
	{{else jobStatus == 'queued'}}
	<td>
		<span style="color: #F00;">Queued</span>
	</td>
	<td>
		<a href="#" onclick="WFAD.killPasswdAudit('${id}'); return false;">Cancel Audit</a>
	</td>
	{{else jobStatus == 'running'}}
	<td>
		<span style="color: #0A0;">Running</span>
	</td>
	<td>
		<a href="#" onclick="WFAD.killPasswdAudit('${id}'); return false;">Stop Audit</a>
	</td>
	{{/if}}
</tr>
</script>
<script type="text/x-jquery-template" id="wfWelcomePasswd">
<div>
	<h3>Premium Feature: Audit your Password Strength</h3>
	<strong><p>Want to know how easily a hacker can crack your passwords?</p></strong>

	<p>
		Wordfence Premium includes password auditing. Using this feature
		we securely test your passwords against a cracking program that hackers use.
		The difference is that we use extremely fast servers in our data center which
		allow us to quickly simulate a complex password cracking attack. We then tell
		you which passwords on your system are weak and help you easily fix the problem.
	</p>

	<p>
		<?php
		if (wfConfig::get('isPaid')){
			?>
			You have upgraded to the premium version of Wordfence and have full access
			to this feature along with our other premium features and priority support.
		<?php
		} else {
		?>
		If you would like access to this premium feature, please
		<a href="https://www.wordfence.com/gnl1pwAuditUp2/wordfence-signup/" target="_blank">upgrade to our premium version</a>.
	</p>
	<?php
	}
	?>
</div>
</script>
