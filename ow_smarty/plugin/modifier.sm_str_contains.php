<?php

function smarty_modifier_sm_str_contains(string $haystack, string $needle): bool
{
    return str_contains($haystack, $needle);
}
