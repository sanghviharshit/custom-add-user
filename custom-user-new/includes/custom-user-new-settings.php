<div class="wrap">
    <h2><?php _e( 'Custom New User', $this->text_domain ) ?></h2>

    <?php
        //Display status message
        if ( isset( $_GET['dmsg'] ) ) { ?>
            <div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
        }

        if ( 'network' == $network ): ?>
            <div id="nse-network-settings">
                <p><?php  _e( 'You can configure the instructions and notes that appears under New User form here.', $this->text_domain ); ?></p>
<!--                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>
-->
                <h3 class="title cun-settings"><?php _e( 'Custom New User Instructions', $this->text_domain ) ?></h3>

                <form method="post" action="">

                    <table  class="form-table cun-settings-preview">
                        <tr class="cun-settings" valign="top">
                            <th scope="row"><?php _e( '(Preview) Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <?php if ( !empty( $this->current_settings['cun_settings']['cun_instructions_content'] ) ) { echo stripslashes($this->current_settings['cun_settings']['cun_instructions_content']); } ?>
                            </td>
                        </tr>

                    </table>

                    <table  class="form-table cun-settings">
                        <tr class="cun-settings" valign="top">
                            <th scope="row"><?php _e( 'Update Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="cun_instructions_content" rows="5" cols="60"><?php if ( !empty( $this->current_settings['cun_settings']['cun_instructions_content'] ) ) { echo stripslashes($this->current_settings['cun_settings']['cun_instructions_content']); } ?></textarea>
                                <p class="description"><?php _e( 'You can use HTML tags as well.', $this->text_domain ); ?></p>
                            </td>
                        </tr>
<!--
                        <tr class="nse-video-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="nse_settings_video_src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings']['nse_settings_video_src'] ) ) { echo $this->current_settings['nse_settings']['nse_settings_video_src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for single video. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23435151/partner_id/1674401?iframeembed=true&amp;playerId=kaltura_player&amp;flashvars[mediaProtocol]=rtmp&amp;flashvars[streamerType]=rtmp&amp;flashvars[streamerUrl]=rtmp://www.kaltura.com:1935&amp;flashvars[rtmpFlavors]=1&amp;&amp;wid=1_f8okstds&amp;entry_id=', $this->text_domain ); ?></p>
                            </td>
                        </tr>
-->

                    </table>

                    <h3 class="title nse-playlist-settings"><?php _e( 'Username Override Settings.', $this->text_domain ) ?></h3>

                    <table class="form-table cun-settings-username">

                        <tr class="cun-settings-username" valign="top">
                            <th scope="row"><?php _e( 'Domains for Email Address', $this->text_domain ); ?></th>
                            <td>
                                <input type="text" name="cun_settings_username_domain" class="regular-text" <?php if ( !empty( $this->current_settings['cun_settings']['cun_settings_username_domain'] ) ) { echo "value='".strtolower(stripslashes($this->current_settings['cun_settings']['cun_settings_username_domain']))."'"; } ?> />
                                <p class="description"><?php _e( 'Comma-separated list of allowed domains for email-id as username. e.g. nyu.edu, poly.edu', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('cun_submit_settings_network'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>

                </form>
            </div>

        <?php else: ?>
<!--
            <div id="nse-site-settings">
                <p><?php  _e( 'You can customize NYU Stream embed plugin related settings here.', $this->text_domain ); ?></p>
                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>

                <h3 class="title nse-vid-settings"><?php _e( 'NYU Stream Embed Settings for Video', $this->text_domain ) ?></h3>

                <form method="post" action="">
                    <table  class="form-table nse-vid-settings">

                        <tr class="nse-vid-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe id', $this->text_domain ); ?></th>
                            <td>
                                <input type="text" name="id" class="regular-text" <?php if ( !empty( $this->current_settings['nse_settings_vid']['id'] ) ) { echo "value=".$this->current_settings['nse_settings_vid']['id']; } ?> />
                                <p class="description"><?php _e( 'id from iframe embed code for single video. e.g. kaltura_player', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                        <tr class="nse-vid-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings_vid']['src'] ) ) { echo $this->current_settings['nse_settings_vid']['src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for single video. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23435151/partner_id/1674401?iframeembed=true&amp;playerId=kaltura_player&amp;flashvars[mediaProtocol]=rtmp&amp;flashvars[streamerType]=rtmp&amp;flashvars[streamerUrl]=rtmp://www.kaltura.com:1935&amp;flashvars[rtmpFlavors]=1&amp;&amp;wid=1_f8okstds&amp;entry_id=', $this->text_domain ); ?></p>
                            </td>
                        </tr>


                    </table>

                    <h3 class="title nse-playlist-settings"><?php _e( 'NYU Stream Embed Settings for Playlist', $this->text_domain ) ?></h3>

                    <table class="form-table nse-playlist-settings">

                        <tr class="nse-playlist-settings" valign="top">
                            <th scope="row"><?php _e( 'iframe src', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="src" rows="5" cols="60"><?php if ( !empty( $this->current_settings['nse_settings_playlist']['src'] ) ) { echo $this->current_settings['nse_settings_playlist']['src']; } ?></textarea>
                                <p class="description"><?php _e( 'src from iframe embed code for playlist. e.g. https://cdnapisec.kaltura.com/p/1674401/sp/167440100/embedIframeJs/uiconf_id/23437711/partner_id/1674401/widget_id/1_zh8d6z1g?iframeembed=true&playerId=kaltura_player_1390404249&flashvars[playlistAPI.autoContinue]=true&flashvars[playlistAPI.autoInsert]=true&flashvars[ks]=&flashvars[playlistAPI.kpl0Id]=', $this->text_domain ); ?></p>
                            </td>
                        </tr>

                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('cun_submit_settings'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>
                </form>
            </div>
-->
        <?php endif; ?>

</div>