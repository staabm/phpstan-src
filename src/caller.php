<?php

function getCallerInfo(): ?string
{
	$dtraces = debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

	foreach ($dtraces as $i => $trace) {
		// search for the first outside caller
		$file = $trace['file'] ?? '';
		if (
			str_contains($file, __FILE__)
		) {
			continue;
		}

		$callersCaller = '';
		if (array_key_exists($i + 1, $dtraces)) {
			$callersTrace = $dtraces[$i + 1];
			if (! isset($callersTrace['file'])) {
				$callersCaller = ($callersTrace['class'] ?? '') . ($callersTrace['type'] ?? '') . $callersTrace['function'];
			} else {
				$callersCaller = basename($callersTrace['file']) . ':' . ($callersTrace['line'] ?? 0);
			}
			$callersCaller .= ' -> ';
		}

		// show direct caller
		if ($file === '') {
			// internal function calls do not provide file/line
			return $callersCaller . ($trace['class'] ?? '') . ($trace['type'] ?? '') . $trace['function'];
		}

		return $callersCaller . basename($file) . ':' . ($trace['line'] ?? 0);
	}

	return null;
}
