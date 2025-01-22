<?php

it('has api/tweet page', function () {
    $response = $this->get('/api/tweet');

    $response->assertStatus(200);
});
