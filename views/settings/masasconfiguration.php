<script type="text/javascript">
    $.getScript('http://timeago.yarp.com/jquery.timeago.js', function(){
        $('abbr.timeago').timeago();
    });
</script>
<style type="text/css">


    .help_message { width:500px; margin-left:164px; padding-top:5px; opacity:0.8; }
    .errors { width:70%; margin:0 20px 20px; background:#FE9; border:1px solid #FC7; }
    .messages { width:70%; margin:0 20px 20px; background:#CFC; border:1px solid #6CF; }
    .error, .message { padding:5px; margin:5px; }
    .error img, .message img { position:relative; top:3px; padding-right:10px; }

    .entry { width:90%; margin:10px 0 10px 30px; padding:10px; }
    .entry .icon { position:relative; top:3px; padding-right:20px; float:left; }
    .entry.passed { background:#CFC; border:1px solid #6CF; }
    .entry.passed .icon { width:16px}
    .entry.failed { background:#FE9; border:1px solid #FC7; }
    .entry .label { display:inline;}
    .entry p { margin-left:30px; float:left;}
    .entry p.created { width:200px;}


    input.button {
        display: inline-block;
        color: #fff;
        font-weight: bold;
        line-height: 27px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.45);
        -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);
        -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        background-color: #54c2d5;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#54c2d5), to(#36b7ce));
        background-image: -webkit-linear-gradient(top, #54c2d5, #36b7ce);
        background-image: -moz-linear-gradient(top, #54c2d5, #36b7ce);
        background-image: -o-linear-gradient(top, #54c2d5, #36b7ce);
        background-image: -ms-linear-gradient(top, #54c2d5, #36b7ce);
        background-image: linear-gradient(top, #54c2d5, #36b7ce);
        filter: progid:dximagetransform.microsoft.gradient(GradientType=0, StartColorStr='#54c2d5', EndColorStr='#36b7ce');
        padding: 5px 15px;
        cursor:pointer;
    }

    input.button:hover {
        text-decoration: none;
        background-color: #36b7ce;
        background-image: -webkit-gradient(linear, left top, left bottom, from(#36b7ce), to(#54c2d5));
        background-image: -webkit-linear-gradient(top, #36b7ce, #54c2d5);
        background-image: -moz-linear-gradient(top, #36b7ce, #54c2d5);
        background-image: -o-linear-gradient(top, #36b7ce, #54c2d5);
        background-image: -ms-linear-gradient(top, #36b7ce, #54c2d5);
        background-image: linear-gradient(top, #36b7ce, #54c2d5);
        filter: progid:dximagetransform.microsoft.gradient(GradientType=0, StartColorStr='#36b7ce', EndColorStr='#54c2d5');
    }

    .clearfix:after {
        content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        line-height: 0;
        height: 0;
    }

    .clearfix {
        display: inline-block;
    }

    html[xmlns] .clearfix {
        display: block;
    }

    * html .clearfix {
        height: 1%;
    }


</style>
<?php echo Form::open(); ?>
    <input type="hidden" name="user_id" value="<? echo $user_id; ?>" />
    <input type="hidden" name="id" value="<? echo $id; ?>" />

    <article class="container base">
		<header class="cf">
			<div class="property-title" style="width:100%;">
				<div class="actions" style="float:right; padding:10px;">
					<input type="submit" value="save and test" class="button" name="save" />
				</div>
				<h1><? echo __('MASAS Configuration') ?></h1>
				<p style="clear:both; padding:10px;"><? echo __('Please complete all the configuration options below') ?></p>
				<p style="clear:both; padding:10px;"><? echo __('These settings are specific to your user account and will not be used by any other users on this Swiftriver instance.') ?></p>
			</div>
		</header>
		<section class="property-parameters">
            <? if (count($errors)): ?>
                <br/>
                <div class="errors">
                    <? foreach ($errors as $message): ?>
                        <p class="error"><img src="http://cdn1.iconfinder.com/data/icons/softwaredemo/PNG/16x16/Warning.png"/><? echo $message; ?></p>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
            <? if (count($messages)): ?>
                <br/>
                <div class="messages">
                    <? foreach ($messages as $message): ?>
                        <p class="message"><img src="http://cdn1.iconfinder.com/data/icons/musthave/16/Information.png" /><? echo $message; ?></p>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
			<div class="parameter">
				<p class="field">MASAS Feed Url</p>
				<input type="text" name="masas_url" value="<? echo $masas_url; ?>" />
                <p class="help_message">
                    <?
                        echo __("The full url of the Masas server starting with 'https://' and ending with /hub/feed. The whole thing ".
                                "should look something like: <em>https://masas.netalerts.ca/hub/feed</em>. (This can be found in the ".
                                "settings panel of the MASAS Viewing Tool).");
                    ?>
                </p>
			</div>
			<div class="parameter">
				<p class="field"><? echo __('Access Code'); ?></p>
				<input type="text" name="masas_secret" value="<? echo $masas_secret; ?>" />
                <p class="help_message">
                    <? echo __('This can be found in the settings panel of the MASAS Viewing Tool.'); ?>
                </p>
			</div>
		</section>
	</article>

    <article class="container base">
        <header class="cf">
            <div class="property-title" style="width:100%;">
                <h1><? echo __('MASAS Activity Log') ?></h1>
            </div>
        </header>
        <section class="property-parameters">
            <br/>
            <? foreach ($log_entries as $entry): ?>
                <div class="entry clearfix <? if ($entry->status == '1'): ?>passed<? else: ?>failed<? endif; ?>">
                    <? if ($entry->status == '1'): ?>
                        <img src="http://cdn1.iconfinder.com/data/icons/sketchdock-ecommerce-icons/ok-blue.png" class="icon" />
                    <? else: ?>
                        <img src="http://cdn1.iconfinder.com/data/icons/softwaredemo/PNG/16x16/Warning.png" class="icon"/>
                    <? endif; ?>
<!--                    <span class="created">--><?// echo date('m/d/Y H:i:s', $entry->created); ?><!--</span>-->
                    <p class="created"><span class="label">When?</span> <abbr class="timeago" title="<? echo date('Y-m-d\TH:i:s\Z', $entry->created); ?>"><? echo date('r', $entry->created); ?></abbr></p>
                    <div class="extras">
                        <p class="masas_url"><span class="label">Sent to:</span> <? echo $entry->masas_url; ?></p>
                    </div>
                </div>
            <? endforeach; ?>
        </section>
    </article>
<?php echo Form::close(); ?>
