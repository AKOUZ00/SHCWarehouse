<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Optimized Barcode Scanner</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        #scanner-container {
            position: relative;
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            overflow: hidden;
            border: 3px solid #333;
            border-radius: 8px;
        }
        #scanner-container canvas.drawingBuffer {
            position: absolute;
            top: 0;
            left: 0;
        }
        .viewport {
            position: relative;
        }
        .scan-region-highlight-horizontal {
            position: absolute;
            border-top: 2px solid red;
            border-bottom: 2px solid red;
            height: 100px;
            left: 0;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            pointer-events: none;
        }
        #result {
            margin: 20px auto;
            padding: 15px;
            max-width: 640px;
            text-align: center;
            background: #f5f5f5;
            border-radius: 8px;
            font-size: 18px;
            transition: background-color 0.3s;
        }
        .detected {
            background-color: #c8e6c9 !important;
        }
        .controls {
            text-align: center;
            margin: 20px 0;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        #detectedCodes {
            margin: 20px auto;
            max-width: 640px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Product Barcode Scanner</h1>
    <div id="scanner-container">
        <div class="viewport">
            <div class="scan-region-highlight-horizontal"></div>
            <!-- Video and canvas will be inserted here by QuaggaJS -->
        </div>
    </div>
    <div id="result">Aim barcode at the red lines</div>
    <div class="controls">
        <button id="start-button">Start Scanner</button>
        <button id="reset-button">Reset</button>
    </div>
    <div id="detectedCodes"></div>

    <script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
    
    <script>
        const startButton = document.getElementById('start-button');
        const resetButton = document.getElementById('reset-button');
        const resultElement = document.getElementById('result');
        const detectedCodesElement = document.getElementById('detectedCodes');
        
        let scannerRunning = false;
        let detectedCodeMap = {};
        let confidenceThreshold = 0.10; // Lower threshold for testing
        let detectionBuffer = []; // Buffer to store recent detections
        let lastDetectedTime = 0;
        
        startButton.addEventListener('click', function() {
            if (scannerRunning) {
                stopScanner();
                startButton.textContent = 'Start Scanner';
            } else {
                startScanner();
                startButton.textContent = 'Stop Scanner';
            }
        });
        
        resetButton.addEventListener('click', function() {
            detectedCodeMap = {};
            detectionBuffer = [];
            detectedCodesElement.innerHTML = '';
            resultElement.textContent = 'Reset completed. Aim barcode at the red lines';
            resultElement.classList.remove('detected');
        });
        
        function startScanner() {
            resultElement.textContent = "Starting camera...";
            
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector("#scanner-container .viewport"),
                    constraints: {
                        width: { min: 640, ideal: 1280, max: 1920 },
                        height: { min: 480, ideal: 720, max: 1080 },
                        aspectRatio: { ideal: 1.7777777778 }, // 16:9 aspect ratio
                        facingMode: "environment"
                    },
                    area: {
                        top: "30%",    // Increase the scan area vertically
                        right: "20%",
                        left: "20%",
                        bottom: "30%"
                    }
                },
                locate: true,
                locator: {
                    patchSize: "large", // Use larger patches for better detection
                    halfSample: false   // Disable half sampling for higher precision
                },
                numOfWorkers: 4,        // Increase the number of workers for better performance
                frequency: 10,          // Increase the frequency for more frequent scans
                decoder: {
                    readers: [
                        // Simplified reader list focused on product barcodes
                        "ean_reader",
                        "ean_8_reader",
                        "upc_reader",
                        "upc_e_reader"
                    ],
                    multiple: false,
                    debug: {
                        showCanvas: true,
                        showPatches: true,
                        showFoundPatches: true,
                        showSkeleton: true,
                        showLabels: true,
                        showPatchLabels: true,
                        showRemainingPatchLabels: true
                    }
                },
                debug: {
                    drawBoundingBox: true,
                    showFrequency: true,
                    drawScanline: true,
                    showPattern: true
                }
            }, function(err) {
                if (err) {
                    console.error("Quagga initialization failed:", err);
                    resultElement.innerHTML = `<span style="color:red">Scanner error: ${err}</span>`;
                    return;
                }
                
                console.log("Quagga initialized successfully");
                scannerRunning = true;
                resultElement.textContent = 'Scanner running - align barcode with red lines';
                Quagga.start();
            });
            
            // Handle processed frames - draw bounding boxes
            Quagga.onProcessed(function(result) {
                const drawingCtx = Quagga.canvas.ctx.overlay;
                const drawingCanvas = Quagga.canvas.dom.overlay;
                
                if (!drawingCtx || !drawingCanvas) return;
                
                drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                
                if (result) {
                    // Draw all potential barcodes (blue boxes)
                    if (result.boxes) {
                        result.boxes.filter(function(box) {
                            return box !== result.box;
                        }).forEach(function(box) {
                            Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "blue", lineWidth: 2});
                        });
                    }
                    
                    // Draw main barcode box (red)
                    if (result.box) {
                        Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "red", lineWidth: 3});
                    }
                    
                    // Draw scan line (red)
                    if (result.codeResult && result.codeResult.code) {
                        Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: "red", lineWidth: 4});
                    }
                }
            });
            
            // Handle successful barcode detection
            Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                const format = result.codeResult.format;
                const confidence = result.codeResult.confidence;
                
                console.log(`Barcode detected: ${code} (${format}) - Confidence: ${confidence.toFixed(2)}`);
                
                // Only process detections above our confidence threshold
                if (confidence > confidenceThreshold) {
                    // Add to detection buffer with expiration
                    const now = Date.now();
                    
                    // Remove expired entries (older than 3 seconds)
                    detectionBuffer = detectionBuffer.filter(entry => 
                        now - entry.timestamp < 3000);
                    
                    // Add current detection
                    detectionBuffer.push({
                        code: code,
                        format: format,
                        confidence: confidence,
                        timestamp: now
                    });
                    
                    // Find consensus among recent detections
                    const consensus = findConsensus(detectionBuffer);
                    
                    // Only process if we have a strong consensus and enough time passed
                    // since last detection (to avoid duplicates)
                    if (consensus && consensus.count >= 3 && now - lastDetectedTime > 2000) {
                        lastDetectedTime = now;
                        
                        // We have a reliable detection
                        resultElement.innerHTML = `
                            <strong>Detected:</strong> ${consensus.code}<br>
                            <strong>Format:</strong> ${consensus.format}<br>
                            <strong>Confidence:</strong> ${(consensus.confidence * 100).toFixed(0)}%
                        `;
                        resultElement.classList.add('detected');
                        
                        // Track unique codes
                        if (!detectedCodeMap[consensus.code]) {
                            detectedCodeMap[consensus.code] = {
                                format: consensus.format,
                                confidence: consensus.confidence,
                                count: 1
                            };
                            updateDetectedList();
                        } else {
                            // Update existing code stats
                            detectedCodeMap[consensus.code].count++;
                            if (consensus.confidence > detectedCodeMap[consensus.code].confidence) {
                                detectedCodeMap[consensus.code].confidence = consensus.confidence;
                            }
                            updateDetectedList();
                        }
                        
                        // Play success sound
                        playBeep();
                        
                        // Briefly pause to avoid rapid-fire detections
                        Quagga.pause();
                        setTimeout(() => {
                            if (scannerRunning) {
                                resultElement.classList.remove('detected');
                                Quagga.start();
                            }
                        }, 1500);
                    }
                }
            });
        }
        
        function stopScanner() {
            if (scannerRunning) {
                Quagga.stop();
                scannerRunning = false;
                resultElement.textContent = 'Scanner stopped';
                resultElement.classList.remove('detected');
            }
        }
        
        // Improved consensus finder for better accuracy
        function findConsensus(detections) {
            if (detections.length < 3) return null; // Require minimum of 3 detections
            
            // Group detections by code
            const grouped = {};
            detections.forEach(detection => {
                if (!grouped[detection.code]) {
                    grouped[detection.code] = [];
                }
                grouped[detection.code].push(detection);
            });
            
            // Find code with highest occurrence and confidence
            let bestCode = null;
            let maxCount = 0;
            let maxConfidence = 0;
            
            Object.keys(grouped).forEach(code => {
                const codeGroup = grouped[code];
                const count = codeGroup.length;
                // Weight recent detections higher
                const avgConfidence = codeGroup.reduce((sum, det) => {
                    const age = Date.now() - det.timestamp;
                    const recencyFactor = Math.max(0, 1 - (age / 3000));
                    return sum + (det.confidence * recencyFactor);
                }, 0) / count;
                
                // Prioritize codes with more detections, then higher confidence
                if (count > maxCount || (count === maxCount && avgConfidence > maxConfidence)) {
                    maxCount = count;
                    maxConfidence = avgConfidence;
                    bestCode = {
                        code: code,
                        format: codeGroup[0].format,
                        confidence: avgConfidence,
                        count: count
                    };
                }
            });
            
            // Verify code length based on format
            if (bestCode) {
                const isValid = validateBarcode(bestCode.code, bestCode.format);
                if (!isValid) {
                    console.log(`Rejected invalid barcode: ${bestCode.code} (${bestCode.format})`);
                    return null;
                }
            }
            
            return bestCode;
        }
        
        // Validate barcode format
        function validateBarcode(code, format) {
            if (!code) return false;
            
            // Check for valid length based on format
            switch(format) {
                case 'ean_13':
                    return code.length === 13 && /^\d{13}$/.test(code);
                case 'ean_8':
                    return code.length === 8 && /^\d{8}$/.test(code);
                case 'upc_a':
                    return code.length === 12 && /^\d{12}$/.test(code);
                case 'upc_e':
                    return code.length === 8 && /^\d{8}$/.test(code);
                default:
                    return true; // Accept other formats as is
            }
        }
        
        function updateDetectedList() {
            const codes = Object.keys(detectedCodeMap);
            if (codes.length === 0) {
                detectedCodesElement.innerHTML = '';
                return;
            }
            
            let html = '<h3>Detected Barcodes:</h3><ul>';
            codes.forEach(code => {
                const item = detectedCodeMap[code];
                html += `
                    <li>
                        <strong>${code}</strong> (${item.format})<br>
                        Confidence: ${(item.confidence * 100).toFixed(0)}% | 
                        Times detected: ${item.count}
                    </li>
                `;
            });
            html += '</ul>';
            
            detectedCodesElement.innerHTML = html;
        }
        
        function playBeep() {
            try {
                const audio = new Audio("data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YT8BAADpAFgCFQMiBDkFUQZqB4oI");
                audio.play();
            } catch (e) {
                console.warn("Could not play audio", e);
            }
        }
    </script>
</body>
</html>