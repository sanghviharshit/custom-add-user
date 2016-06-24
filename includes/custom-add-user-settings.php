<div class="wrap">
    <h2><?php _e( 'Custom New User', $this->text_domain ) ?></h2>

    <?php
        //Display status message
        if ( isset( $_GET['dmsg'] ) ) { ?>
            <div id="message" class="updated fade"><p><?php echo urldecode( $_GET['dmsg'] ); ?></p></div><?php
        }
        if ( 'network' == $network ): ?>
            <div id="cau-network-settings">
                <p><?php  _e( 'You can configure the instructions and notes that appears under New User form here.', $this->text_domain ); ?></p>
<!--                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>
-->
                <h3 class="title cau-settings"><?php _e( 'Custom New User Instructions', $this->text_domain ) ?></h3>

                <form method="post" action="">

                    <table  class="form-table cau-settings-preview">
                        <tr class="cau-settings" valign="top">
                            <th scope="row"><?php _e( '(Preview) Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <?php if ( !empty( $this->current_settings['cau_settings']['cau_instructions_content'] ) ) { echo stripslashes($this->current_settings['cau_settings']['cau_instructions_content']); } ?>
                            </td>
                        </tr>

                    </table>

                    <table  class="form-table cau-settings">
                        <tr class="cau-settings" valign="top">
                            <th scope="row"><?php _e( 'Update Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="cau_instructions_content" rows="5" cols="60"><?php if ( !empty( $this->current_settings['cau_settings']['cau_instructions_content'] ) ) { echo stripslashes($this->current_settings['cau_settings']['cau_instructions_content']); } ?></textarea>
                                <p class="description"><?php _e( 'You can use HTML tags as well.', $this->text_domain ); ?></p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('cau_submit_settings_network'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>

                </form>
            </div>

        <?php else: ?>
            <div id="cau-site-settings">
                <p><?php  _e( 'You can configure the instructions and notes that appears under New User form here.', $this->text_domain ); ?></p>
<!--                <p><?php  _e( 'To get going, first <a href="http://www.nyu.edu/servicelink/KB0012363">learn how the plugin works.</a>, find out iframe embed code from NYU stream, and identify various elements in the embed code such as, video id, plalist id, etc.', $this->text_domain ); ?></p>
-->
                <h3 class="title cau-settings"><?php _e( 'Custom New User Instructions', $this->text_domain ) ?></h3>

                <form method="post" action="">

                    <table  class="form-table cau-settings-preview">
                        <tr class="cau-settings" valign="top">
                            <th scope="row"><?php _e( '(Preview) Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <?php if ( !empty( $this->current_settings['cau_settings']['cau_instructions_content'] ) ) { echo stripslashes($this->current_settings['cau_settings']['cau_instructions_content']); } ?>
                            </td>
                        </tr>

                    </table>

                    <table  class="form-table cau-settings">
                        <tr class="cau-settings" valign="top">
                            <th scope="row"><?php _e( 'Update Instructions/Notes', $this->text_domain ); ?></th>
                            <td>
                                <textarea name="cau_instructions_content" rows="5" cols="60"><?php if ( !empty( $this->current_settings['cau_settings']['cau_instructions_content'] ) ) { echo stripslashes($this->current_settings['cau_settings']['cau_instructions_content']); } ?></textarea>
                                <p class="description"><?php _e( 'You can use HTML tags as well.', $this->text_domain ); ?></p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <?php wp_nonce_field('cau_submit_settings'); ?>
                        <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Save Changes', $this->text_domain ); ?>" />
                    </p>

                </form>
            </div>
        <?php endif; ?>

</div>