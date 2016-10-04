<?php
/**
 * User: Jeffery
 * Date: 3/6/2016
 * Time: 11:41 AM
 */
?>
<div id="sureRevoke" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">Are you sure you want to revoke this campaign? </div>
            <div class="modal-footer">
                <button id="yes" class="btn btn-danger btn-sm">Yes</button>
                <button id="no" class="btn btn-default btn-sm">No</button>
            </div>
        </div>
    </div>
</div>
<div id="cantRevoke" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="display: none;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">This campaign can not be revoked as it has already been accepted. Please contact <a href="/contact-us">GoSocialMedia Support</a> for further assistance.</div>
            <div class="modal-footer">
                <button id="ok" class="btn btn-danger btn-sm">Ok</button>
            </div>
        </div>
    </div>
</div>
